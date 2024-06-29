<?php

namespace App\Http\Controllers\Site\Teacher;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Site\Teacher\AnalysisResource;
use App\Services\Site\Teacher\AnalysisService;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalysisController extends BaseApiController
{
    public function __construct(protected AnalysisService $analysisSer)
    {
        //
    }

    public function index()
    {
        $data = $this->analysisSer->analysis();
        try {
            return $this->sendResponse(AnalysisResource::make($data->toArray()));
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
