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

//        if($priceDiff < 0){
//            $price = round(array_sum($this->downWeightArr) / count($this->downWeightArr));
//        }else{
//            $price = round(array_sum($this->upWeightArr) / count($this->upWeightArr));
//        }
//        return $originPrice + ($price * $originPrice / 100);

        $tmpW = (array_sum($this->downWeightArr) + array_sum($this->upWeightArr)) / (count($this->downWeightArr) + count($this->upWeightArr));
        return $originPrice + ($tmpW * $originPrice / 100);

    }
}
