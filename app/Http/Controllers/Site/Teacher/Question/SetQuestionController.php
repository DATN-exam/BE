<?php

namespace App\Http\Controllers\Site\Teacher\Question;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\Question\SetQuestionRequest;
use App\Http\Requests\Site\Teacher\Question\SetQuestionUpdateRequest;
use App\Http\Resources\Site\Teacher\Question\SetQuestionResource;
use App\Models\SetQuestion;
use App\Services\Site\Teacher\Question\SetQuetionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SetQuestionController extends BaseApiController
{
    public function __construct(protected SetQuetionService $setQuestionSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $setQuestions = $this->setQuestionSer->setRequest($rq)->paginate();
            return $this->sendResourceResponse(
                SetQuestionResource::collection($setQuestions)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function store(SetQuestionRequest $rq)
    {
        try {
            $setQuestion = $this->setQuestionSer->setRequestValidated($rq)->create();
            return $this->sendResponse(
                SetQuestionResource::make($setQuestion)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function update(SetQuestion $setQuestion, SetQuestionUpdateRequest $rq)
    {
        try {
            $this->setQuestionSer->setRequestValidated($rq)->update($setQuestion);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
