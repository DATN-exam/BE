<?php

use Carbon\Carbon;

if (!function_exists('getToday')) {
    function getToday()
    {
        return Carbon::now()->format(config('define.date_format'));
    }
}
