<?php

namespace App\Observers;

use App\Enums\Classroom\ClassroomKeyStatus;
use App\Models\ClassroomKey;
use Carbon\Carbon;

class ClassroomKeyObserver
{
    public function creating(ClassroomKey $key)
    {
        $key->status = $key->status ?? ClassroomKeyStatus::ACTIVE;
        $key->quantity = $key->quantity ?? 9999;
        $key->remaining = $key->quantity;
        $key->expired = $key->expired ?? Carbon::maxValue()->format(config('define.date_format'));
    }
}
