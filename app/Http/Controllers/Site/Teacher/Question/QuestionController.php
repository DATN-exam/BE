<?php

namespace App\Http\Controllers\Site\Teacher\Question;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\Question\QuestionRequest;
use App\Http\Requests\Site\Teacher\Question\QuestionUpdateRequest;
use App\Http\Resources\Site\Teacher\Question\QuestionResource;
use App\Models\Question;
use App\Models\SetQuestion;
use App\Services\Site\Teacher\Question\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class QuestionController extends BaseApiController
{
    public function __construct(protected QuestionService $questionService)
    {
        //
    }

    public function store(SetQuestion $setQuestion, QuestionRequest $rq)
    {
        $question = $this->questionService->setRequestValidated($rq)->handleCreate($setQuestion);
        return $this->sendResponse(
            QuestionResource::make($question)
        );
        try {
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function index(SetQuestion $setQuestion, Request $rq)
    {
        try {
            $questions = $this->questionService->setRequest($rq)->paginate($setQuestion);
            return $this->sendResourceResponse(
                QuestionResource::collection($questions)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function show(SetQuestion $setQuestion, Question $question)
    {
        try {
            return $this->sendResourceResponse(
                QuestionResource::make($question->load('answers'))
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function update(SetQuestion $setQuestion, Question $question, QuestionUpdateRequest $rq)
    {
        $this->questionService->setRequestValidated($rq)->handleUpdate($question);
        try {
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
