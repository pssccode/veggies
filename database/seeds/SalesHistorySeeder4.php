<?php

use Illuminate\Database\Seeder;

class SalesHistorySeeder4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $year = 2020;
        $cultureId = 1;
        $userId = 2;
        $now = \Carbon\Carbon::now()->toDateTimeString();

        $items = [
            [14,7,267,17,null],
            [16,7,227,17,null],
            [18,7,269,14,null],
            [20,7,238,7,null],
            [22,7,232,11,null],
            [24,7,228,15,null],
            [26,7,186,15,null],
            [28,7,184,13,null],
            [30,7,196,10,null],

            [1,8,229,8.5,null],
            [3,8,144,10,null],
            [5,8,154,15,null],
            [7,8,169,16,null],
            [9,8,199,14,null],
            [11,8,192,8,null],
            [13,8,141,10,null],
            [15,8,110,15,null],
            [17,8,125,20,null],
            [19,8,136,15,null],
            [21,8,134,12.5,null],
            [23,8,115,10,null],
            [25,8,85,8,null],
            [27,8,91,13,null],
            [29,8,67,11.5,null],

            [1,9,98,8,null],
            [10,9,44,10,null],
            [15,9,23,10,null],
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
