<?php

namespace EC\Stats;

use Silex;

class Stats implements StatsInterface
{
    protected $app;

    public function __construct(Silex\Application $app)
    {
        $this->app = $app;
    }

    protected function getJsTimestamp($time)
    {
        return strtotime($time . 'UTC') * 1000; // Javascript timestamps are in miliseconds
    }

    protected function getReadings(array $input, $months = false)
    {
        $readings = array();

        if (count($input)) {
            $dateType           = $months ? 'date' : 'datetime'; // date or datetime?
            $lastIndex          = count($input) - 1; // Amount of elements in array
            $readings['first']  = $this->getJsTimestamp($input[0][$dateType]); // first recorded time in the array
            $readings['last']   = $this->getJsTimestamp($input[$lastIndex][$dateType]); // last recorded time in the array
        }

        return $readings;
    }
}