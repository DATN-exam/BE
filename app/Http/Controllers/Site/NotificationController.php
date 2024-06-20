<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\NotificationResource;
use App\Services\Site\NotificationService;
use Throwable;

class NotificationController extends BaseApiController
{
    public function __construct(protected NotificationService $notiSer)
    {
        //
    }

    public function index()
    {
        try {
            $notifications = $this->notiSer->getAll();
            return $this->sendResponse(NotificationResource::collection($notifications));
        } catch (Throwable $e) {
            return $this->sendError();
        }
    }
}
