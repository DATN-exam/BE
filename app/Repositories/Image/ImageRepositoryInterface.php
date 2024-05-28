<?php

namespace App\Repositories\Image;

use App\Models\Question;
use App\Repositories\RepositoryInterface;

interface ImageRepositoryInterface extends RepositoryInterface
{
    /**
     * Save many
     * @param $model
     * @param $data
     * @return mixed
     */
    public function saveMany($model, $data);

    public function getImageDelete($ids, Question $question);
}
