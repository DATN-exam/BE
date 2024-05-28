<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function getModel()
    {
        return Image::class;
    }

    public function saveMany($model, $data)
    {
        return $model->images()->saveMany($data);
    }

    public function getImageDelete($ids, Model $model)
    {
        return $model->images()->whereNotIn('id', $ids)->get();
    }
}
