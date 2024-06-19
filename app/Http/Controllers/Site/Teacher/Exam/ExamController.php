<?php

namespace App\Http\Controllers\Site\Teacher\Exam;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\Exam\ExamRequest;
use App\Http\Resources\Site\Teacher\ExamResource;
use App\Models\Classroom;
use App\Models\Exam;
use App\Services\Site\Teacher\Exam\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExamController extends BaseApiController
{
    public function __construct(protected ExamService $examSer)
    {
        //
    }

    public function store(Classroom $classroom, ExamRequest $rq)
    {
        try {
            $exam = $this->examSer->setRequestValidated($rq)->create($classroom);
            return $this->sendResponse(
                ExamResource::make($exam)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function update(Classroom $classroom, Exam $exam, ExamRequest $rq)
    {
        try {
            $this->examSer->setRequestValidated($rq)->update($exam);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function index(Classroom $classroom, Request $rq)
    {
        try {
            $exams = $this->examSer->setRequest($rq)->getList($classroom);
            return $this->sendResourceResponse(
                ExamResource::collection($exams)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function destroy(Classroom $classroom, Exam $exam)
    {
        try {
            $this->examSer->destroy($exam);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}