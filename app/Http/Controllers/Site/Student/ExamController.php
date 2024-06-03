<?php

namespace App\Http\Controllers\Site\Student;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Site\Student\ExamHistoryResource;
use App\Http\Resources\Site\Student\ExamResource;
use App\Models\Classroom;
use App\Models\Exam;
use App\Services\Site\Student\ExamService;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExamController extends BaseApiController
{
    public function __construct(protected ExamService $examSer)
    {
        //
    }

    public function index(Classroom $classroom)
    {
        try {
            $exams = $this->examSer->all($classroom);
            return $this->sendResponse(
                ExamResource::collection($exams)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function show(Classroom $classroom, Exam $exam)
    {
        try {
            return $this->sendResponse(
                ExamResource::make($exam)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function start(Classroom $classroom, Exam $exam)
    {
        try {
            $data = $this->examSer->start($exam);
            return $this->sendResponse(
                ExamHistoryResource::make($data)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
