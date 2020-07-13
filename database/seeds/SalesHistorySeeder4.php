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
            [16,6,407,12,4884],
            [18,6,439,19,8341],
            [20,6,378,18,6804],
            [22,6,430,16.5,7095],
            [24,6,411,23,9453],
            [26,6,285,19,5415],
            [28,6,371,21,7791],
            [30,6,338,18,6084],
            [2,7,335,10,2250],
            [4,7,333,6,1998],
            [6,7,325,8,2600],
            [8,7,256,12,3072],
            [10,7,214,16,3424],
            [12,7,218,15,3270],
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
