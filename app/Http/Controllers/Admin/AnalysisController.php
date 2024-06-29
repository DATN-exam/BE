<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Services\Admin\AnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalysisController extends BaseApiController
{
    public function __construct(protected AnalysisService $analysisSer)
    {
    }

    public function index()
    {
        try {
            $data = $this->analysisSer->analysis();
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function newUserMonthly(Request $rq)
    {
        try {
            $data = $this->analysisSer->setRequest($rq)->newUserMonthly();
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function newClassroomMonthly(Request $rq)
    {
        try {
            $data = $this->analysisSer->setRequest($rq)->newClassroomMonthly();
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
