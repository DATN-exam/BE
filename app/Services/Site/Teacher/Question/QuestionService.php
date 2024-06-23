<?php

namespace App\Services\Site\Teacher\Question;

use App\Enums\Question\QuestionLevel;
use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use App\Models\Question;
use App\Models\SetQuestion;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Services\BaseService;
use App\Services\WordService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;


class QuestionService extends BaseService
{
    public function __construct(
        protected QuestionRepositoryInterface $questionRepo,
        protected AnswerService $answerSer,
        protected ImgQuestionService $imgSer,
        protected WordService $wordService
    ) {
        //
    }

    public function handleCreate(SetQuestion $setQuestion)
    {
        DB::beginTransaction();
        $question = $this->create($setQuestion);
        $this->answerSer->insert($this->data['answers'], $question);
        $question->load(['answers', 'images']);
        DB::commit();
        return $question;
        try {
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function create(SetQuestion $setQuestion)
    {

        $questionHmtl = $this->data["question"];
        $data = replaceImgTagsWithKey($questionHmtl);
        $questionHmtl = $data['html'];
        $mapping = $data['mapping'];
        $question =  $this->questionRepo->create([
            "set_question_id" => $setQuestion->id,
            "question" => $questionHmtl,
            "score" => 10,
            "is_testing" => $this->data["is_testing"],
            "status" => QuestionStatus::getValueByKey($this->data["status"]),
            "type" => QuestionType::getValueByKey($this->data["type"]),
            "level" => QuestionLevel::getValueByKey($this->data["level"])
        ]);
        if (!empty($mapping)) {
            $newImages = $this->imgSer->handleSaveImages($question, $mapping);
            foreach ($newImages as $key => $image) {
                $questionHmtl = Str::replace($key, $image->id, $questionHmtl);
            }
        }
        $question->question = $questionHmtl;
        $question->save();
        return $question;
    }

    public function paginate(SetQuestion $setQuestion)
    {
        return $this->questionRepo->paginate($setQuestion, $this->data);
    }

    public function exportWord(SetQuestion $setQuestion)
    {
        $questions = $this->questionRepo->exportWord($setQuestion, $this->data);
        return $this->wordService->exportQuestions($questions, $setQuestion);
    }

    public function handleUpdate(Question $question)
    {
        try {
            DB::beginTransaction();
            $this->update($question);
            $this->answerSer->deleteAnswers($question, $this->data['answers_update']);
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
        $questionHmtl = $this->handleImageQuestion($question);
        $data = [
            "question" => $questionHmtl,
            "is_testing" => $this->data['is_testing'],
            "status" => QuestionStatus::getValueByKey($this->data['status']),
            "type" => QuestionType::getValueByKey($this->data['type']),
            "level" => QuestionLevel::getValueByKey($this->data['level']),
        ];
        return $this->questionRepo->update($question, $data);
    }

    private function handleImageQuestion(Question $question)
    {
        $questionHmtl = $this->data['question'];
        $data = getImagesQuestion($questionHmtl);
        $images = $data['images'];
        $this->imgSer->handelDeleteImages($question, $images);
        $questionHmtl = $data['html'];
        $data = replaceImgTagsWithKey($questionHmtl);
        $questionHmtl = $data['html'];
        $mapping = $data['mapping'];
        if (!empty($mapping)) {
            $newImages = $this->imgSer->handleSaveImages($question, $mapping);
            foreach ($newImages as $key => $image) {
                $questionHmtl = Str::replace($key, $image->id, $questionHmtl);
            }
        }
        return $questionHmtl;
    }
}
