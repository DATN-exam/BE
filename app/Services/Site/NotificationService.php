<?php

namespace App\Services\Site;

use App\Services\BaseService;

class NotificationService extends BaseService
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        $user = auth('api')->user();
        return $user->notifications;
    }
}
