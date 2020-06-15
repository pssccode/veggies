<?php


namespace App\Helpers;


use App\Models\SalesHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PredictorHelper
{
    public $date;
    public $cultureId;
    public $userId;
    public $dateCarbon;
    public $searchingYear;
    public $yearlyYears;
    public $upPricesArr = [];
    public $downPricesArr = [];
    public $upWeightArr = [];
    public $downWeightArr = [];
    public $thisYearSales;

    public function __construct($date, $cultureId, $userId)
    {
        $this->date = $date;
        $this->cultureId = $cultureId;
        $this->userId = $userId;
        $this->init();
    }

    public function init()
    {
        // Проверить есть ли в таблице результатов
        $this->dateCarbon = Carbon::parse($this->date);
        $this->searchingYear = $this->dateCarbon->copy()->format('Y');
        $this->thisYearSales = SalesHistory::where([
            ['culture_id', '=', $this->cultureId],
            ['user_id', '=', $this->userId]
        ])
            ->whereDate('date', '<', $this->date)
            ->whereRaw("DATE_FORMAT(date, '%Y') = " . $this->searchingYear)
            ->orderBy('date', 'desc')
            ->limit(3)
            ->get();
        if (count($this->thisYearSales) == 0) {
            return false;
        }

        $checkOtherYears = $this->getEarlyYearsSales();
        if(!$checkOtherYears){
            return false;
        }
    }

    public function getEarlyYearsSales()
    {
        $years = SalesHistory::selectRaw("distinct(DATE_FORMAT(date, '%Y')) as year")
            ->whereRaw("DATE_FORMAT(date, '%Y') < " . $this->searchingYear)
            ->orderBy('date', 'desc')
            ->get();

        if(count($years) == 0){
            return false;
        }

        $this->yearlyYears = [];
        $tmp = [];
        $resultsPrices = [];
        $resultsWeight = [];

        foreach ($years as $year){
            $nextDay = $this->dateCarbon->copy()->addDays(1)->setYear($year->year)->toDateString();
            $tmp[] = SalesHistory::where([
                ['culture_id', '=', $this->cultureId],
                ['user_id', '=', $this->userId]
            ])
                ->whereDate('date', '<=', $nextDay)
                ->whereRaw("DATE_FORMAT(date, '%Y') = " . $year->year)
                ->orderBy('date', 'desc')
                ->limit(4)
                ->get();
        }

        if(count($tmp) == 0){
            return false;
        }

        foreach ($tmp as $item){
            $y = Carbon::parse($item[0]->date)->format('Y');
            $itemCount = count($item);
            $resultsPrices[$y] = round((($item[$itemCount - 1]->price - $item[0]->price) * -1) * 100 / $item[$itemCount - 1]->price);
            $resultsWeight[$y] = round((($item[$itemCount - 1]->weight - $item[0]->weight) * -1) * 100 / $item[$itemCount - 1]->weight);
            if($resultsPrices[$y] < 0){
                $this->downPricesArr[] = $resultsPrices[$y];
            }else{
                $this->upPricesArr[] = $resultsPrices[$y];
            }
            if($resultsWeight[$y] < 0){
                $this->downWeightArr[] = $resultsWeight[$y];
            }else{
                $this->upWeightArr[] = $resultsWeight[$y];
            }
        }
    }

    public function getPricePrediction()
    {
        $cnt = count($this->thisYearSales);
        $originPrice = $this->thisYearSales[$cnt - 1]->price;
        $priceDiff = ($this->thisYearSales[$cnt - 1]->price - $this->thisYearSales[0]->price) * -1;

        if($priceDiff < 0){
            $price = round(array_sum($this->downPricesArr) / count($this->downPricesArr));
        }else{
            $price = round(array_sum($this->upPricesArr) / count($this->upPricesArr));
        }
        return $originPrice + ($price * $originPrice / 100);
    }

    public function getWeightPrediction()
    {
        $cnt = count($this->thisYearSales);
        $originPrice = $this->thisYearSales[$cnt - 1]->weight;
        $priceDiff = ($this->thisYearSales[$cnt - 1]->weight - $this->thisYearSales[0]->weight) * -1;
        $pricePercent = round($priceDiff * 100 / $this->thisYearSales[$cnt - 1]->weight);

        $downCount = count($this->downWeightArr);
        $upCount = count($this->upWeightArr);
        $diffValuePercent = $pricePercent;
        $diffValue = 9999;
        $digit = 0;

        if($downCount > 0){
            foreach ($this->downWeightArr as $item) {
                $digit = $pricePercent + $item;
                if($digit < $diffValue){
                    $diffValuePercent = $item;
                    $diffValue = $digit;
                }
            }
        }
        if($upCount > 0){
            foreach ($this->upWeightArr as $item) {
                $digit = $pricePercent + $item;
                if($digit < $diffValue){
                    $diffValuePercent = $item;
                    $diffValue = $digit;
                }
            }
        }

        $correct = 0;

        if($priceDiff < 0){
            $correct = array_sum($this->upWeightArr) / $upCount;
        }else{
            $correct = array_sum($this->downWeightArr) / $downCount;
        }

        $correct = $correct * 0.1;
        $tmpW = $this->thisYearSales[0]->weight - (round(($diffValuePercent < 0 ? $diffValuePercent * -1 : $diffValuePercent) * $this->thisYearSales[0]->weight / 100));

        return $tmpW + (round($tmpW * $correct / 100));
    }
}
