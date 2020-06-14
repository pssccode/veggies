<?php

use Illuminate\Database\Seeder;

class SalesHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $year = 2019;
        $cultureId = 1;
        $userId = 2;
        $now = \Carbon\Carbon::now()->toDateTimeString();

        $items = [
            [8,5,20,25,462],
            [11,5,60,24,1440],
            [13,5,150,22,3300],
            [15,5,201,18,3618],
            [17,5,200,20,4000],
            [19,5,426,11,4680],
            [21,5,260,10,2600],
            [23,5,136,17,null],
            [25,5,160,17,null],
            [27,5,508,16,null],
            [29,5,532,24,7460],
            [31,5,389,7,2720],
            [2,6,245,5,null],
            [4,6,533,8,4260],
            [6,6,727,8,5816],
            [8,6,401,8.5,3400],
            [10,6,197,12,null],
            [12,6,442,12,5304],
            [14,6,661,4,null],
            [16,6,286,6,1430],
            [18,6,187,5,null],
            [20,6,366,6.5,2379],
            [22,6,411,5,2055],
            [24,6,260,4.5,1300],
            [26,6,151,7,null],
            [28,6,249,12,2988],
            [30,6,260,10,2600],
            [2,7,220,9,1980],
            [4,7,289,6,null],
            [6,7,332,5,1660],
            [8,7,268,6,1608],
            [10,7,187,12,2244],
            [12,7,239,15,null],
            [14,7,284,9,null],
            [16,7,245,12,2940],
            [18,7,273,11,3003],
            [20,7,242,10,2420],
            [22,7,294,8,2352],
            [25,7,409,6,null],
            [28,7,322,8,2576],
            [30,7,130,8,1040],
            [1,8,200,9,1800],
            [3,8,174,9,1566],
            [5,8,168,10,1680],
            [7,8,117,14,1638],
            [9,8,170,15,2550],
            [11,8,189,9,1701],
            [13,8,118,9,null],
            [15,8,138,8.5,null],
            [17,8,129,10,1290],
            [19,8,96,12.5,null],
            [7,9,344,7,2400],
            [8,9,378,7,2640],
            [10,9,114,5,570],
        ];

        $toInsert = [];

        foreach ($items as $item){
            $dt = \Carbon\Carbon::create($year, $item[1], $item[0])->toDateString();
            $checkExist = \App\Models\SalesHistory::where([
                'user_id' => $userId,
                'culture_id' => $cultureId,
                'date' => $userId,
                'price' => $item[3],
                'weight' => $item[2],
            ])->exists();

            if($checkExist){
                continue;
            }

            $toInsert[] = [
                'user_id' => $userId,
                'culture_id' => $cultureId,
                'price' => $item[3],
                'weight' => $item[2],
                'sum' => isset($item[4]) ? $item[4] : ($item[2] * $item[3]),
                'date' => $dt,
                'created_at' => $now,
            ];
        }

        \App\Models\SalesHistory::insert($toInsert);

    }
}
