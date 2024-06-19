<?php

namespace App\Http\Controllers\Site\Student;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Student\Exam\ChangeAnswerRequest;
use App\Http\Resources\Site\Student\ExamHistoryResource;
use App\Http\Resources\Site\Student\ExamResource;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamAnwser;
use App\Models\ExamHistory;
use App\Services\Site\Student\ExamService;
use Exception;
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
        $exams = $this->examSer->all($classroom);
        return $this->sendResponse(
            ExamResource::collection($exams)
        );
        try {
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
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function getCurrent(Classroom $classroom, Exam $exam)
    {
        try {
            $data = $this->examSer->getCurrent($exam);
            return $this->sendResponse(
                $data === null ? null : ExamHistoryResource::make($data)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function getExamHistoryDetail(Classroom $classroom, Exam $exam, ExamHistory $examHistory)
    {
        try {
            $examHistory->load(['exam', 'examAnswers', 'examAnswers.question', 'examAnswers.question.answers', 'exam.setQuestion']);
            return $this->sendResponse(
                ExamHistoryResource::make($examHistory)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function changeAnswer(ExamHistory $examHistory, ExamAnwser $examAnwser, ChangeAnswerRequest $rq)
    {
        try {
            $this->examSer->setRequestValidated($rq)->changeAnwser($examAnwser);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function submit(ExamHistory $examHistory)
    {
        try {
            $this->examSer->submit($examHistory);
            return $this->sendResponse([
                'message' => __('alert.exam.submit.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
