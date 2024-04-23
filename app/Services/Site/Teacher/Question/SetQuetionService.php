<?php

namespace App\Services\Site\Teacher\Question;

use App\Enums\Question\SetQuestionStatus;
use App\Models\SetQuestion;
use App\Repositories\SetQuestion\SetQuestionRepositoryInterface;
use App\Services\BaseService;


class SetQuetionService extends BaseService
{
    public function __construct(
        protected SetQuestionRepositoryInterface $setQuestionRepo
    ) {
        //
    }

    public function create()
    {
        $teacher = auth('api')->user();
        if (isset($this->data['status'])) {
            $this->data['status'] = SetQuestionStatus::getValueByKey($this->data['status']);
        }
        return $this->setQuestionRepo->create([
            ...$this->data,
            'teacher_id' => $teacher->id
        ]);
    }

    public function update(SetQuestion $setQuestion)
    {
        if (isset($this->data['status'])) {
            $this->data['status'] = SetQuestionStatus::getValueByKey($this->data['status']);
        }
        return $this->setQuestionRepo->update($setQuestion, $this->data);
    }

    public function paginate()
    {
        $teacher = auth('api')->user();
        return $this->setQuestionRepo->paginateOfTeacher($teacher->id, $this->data);
    }
}
