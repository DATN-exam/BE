<?php

namespace App\Http\Controllers\Site\Teacher\Exam;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\Exam\ExamRequest;
use App\Http\Resources\Site\Teacher\ExamHistoryResource;
use App\Http\Resources\Site\Teacher\ExamResource;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamHistory;
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

    public function getTop(Classroom $classroom, Exam $exam)
    {
        try {
            $data = $this->examSer->getTop($exam);
            return $this->sendResponse(
                ExamHistoryResource::collection($data)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function getTopExport(Classroom $classroom, Exam $exam)
    {
        try {
            $data = $this->examSer->getTopExport($exam);
            return $this->sendCsvResponse($data, 'ranking.xlsx');
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function analysis(Classroom $classroom, Exam $exam)
    {
        try {
            $data = $this->examSer->analysis($exam);
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function getExamHistoryDetail(Classroom $classroom, Exam $exam, ExamHistory $examHistory)
    {
        try {
            $examHistory->load(['exam', 'student', 'examAnswers', 'examAnswers.question', 'examAnswers.question.answers', 'exam.setQuestion']);
            return $this->sendResponse(
                ExamHistoryResource::make($examHistory)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
