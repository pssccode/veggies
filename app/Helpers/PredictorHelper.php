<?php


namespace App\Helpers;


class PredictorHelper
{
    public $date;
    public $cultureId;
    public $userId;

    public function __construct($date, $cultureId, $userId)
    {
        $this->date = $date;
        $this->cultureId = $cultureId;
        $this->userId = $userId;
    }
}
