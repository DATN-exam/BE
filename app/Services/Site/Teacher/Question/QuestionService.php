<?php

namespace App\Services\Site\Teacher\Question;

use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use App\Models\Question;
use App\Models\SetQuestion;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuestionService extends BaseService
{
    public function __construct(
        protected QuestionRepositoryInterface $questionRepo,
        protected AnswerService $answerSer,
    ) {
        //
    }

    public function handleCreate(SetQuestion $setQuestion)
    {
        try {
            DB::beginTransaction();
            $question = $this->create($setQuestion);
            $this->answerSer->insert($this->data['answers'], $question);
            $question->load('answers');
            DB::commit();
            return $question;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function create(SetQuestion $setQuestion)
    {
        return $this->questionRepo->create([
            "set_question_id" => $setQuestion->id,
            "question" => $this->data["question"],
            "score" => $this->data["score"],
            "is_testing" => $this->data["is_testing"],
            "status" => QuestionStatus::getValueByKey($this->data["status"]),
            "type" => QuestionType::getValueByKey($this->data["type"]),
        ]);
    }

    public function paginate(SetQuestion $setQuestion)
    {
        return $this->questionRepo->paginate($setQuestion, $this->data);
    }

    public function handleUpdate(Question $question)
    {
        DB::beginTransaction();
        try {
            $this->update($question);
            if (!empty($this->data['answers_delete'])) {
                $this->answerSer->deleteAnswers($this->data['answers_delete']);
            }
            if (!empty($this->data['answers_update'])) {
                $this->answerSer->updateAnswers($this->data['answers_update']);
            }
            if (!empty($this->data['answers_add'])) {
                $this->answerSer->insert($this->data['answers_add'], $question);
            }
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            return throw $e;
        }
    }

    private function update(Question $question)
    {
        $data = [
            "question" => $this->data['question'],
            "is_testing" => $this->data['is_testing'],
            "status" => QuestionStatus::getValueByKey($this->data['status']),
        ];
        return $this->questionRepo->update($question, $data);
    }
}
