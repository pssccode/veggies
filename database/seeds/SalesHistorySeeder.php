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
        $year = 2020;
        $cultureId = 1;
        $userId = 2;
        $now = \Carbon\Carbon::now()->toDateTimeString();

        $items = [
            [
                10,5,37,28,1036
            ],
            [
                12,5,102,27,2754
            ],
            [
                14,5,158,32,5056
            ],
            [
                16,5,257,25,6425
            ],
            [
                19,5,312,18,5616
            ],
            [
                21,5,177,23,4071
            ],
            [
                23,5,223,25,5575
            ],
            [
                25,5,287,28,8036
            ],
            [
                27,5,345,23,7935
            ],
            [
                29,5,365,25,9125
            ],
            [
                31,5,307,24,7368
            ],
            [
                2,6,322,23,7406
            ],
            [
                4,6,162,24,3888
            ],
        ];

        $toInsert = [];

        foreach ($items as $item){
            $dt = \Carbon\Carbon::create($year, $item[1], $item[0])->toDateString();
            $checkExist = \App\Models\SalesHistory::where([
                'user_id' => $userId,
                'culture_id' => $cultureId,
                'date' => $userId
            ])->exists();

            if($checkExist){
                continue;
            }

            $toInsert[] = [
                'user_id' => $userId,
                'culture_id' => $cultureId,
                'price' => $item[3],
                'weight' => $item[2],
                'sum' => $item[4],
                'date' => $dt,
                'created_at' => $now,
            ];
        }

        \App\Models\SalesHistory::insert($toInsert);

    }
}
