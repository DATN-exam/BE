<?php

namespace App\Services\Site\Teacher\Question;

use App\Models\Image;
use App\Models\Question;
use App\Repositories\Image\ImageRepositoryInterface;
use App\Services\FileService;

class ImgQuestionService
{
    private $path;

    public function __construct(
        protected ImageRepositoryInterface $imgRep,
        protected FileService $fileSer
    ) {
        $this->path = config('define.path.question');
    }

    public function saveMany($files, $question)
    {
        $data = [];
        foreach ($files as $file) {
            $image = new Image();
            $newPath = generatePathFile($this->path, $question->id, $file->getClientOriginalExtension());
            $image->old_name = $file->getClientOriginalName();
            $image->path = $newPath;
            $data[] = $image;
        }
        $images = $this->imgRep->saveMany($question, $data);
        dd($images);
        foreach ($files as $index => $file) {
            $this->fileSer->save($data[$index]->path, $file);
        }
        return;
    }

    public function insert($filenames, $motoId)
    {
        $data = collect($filenames)->map(function ($filename) use ($motoId) {
            return collect([
                'path' => $filename,
                'moto_id' => $motoId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        })->toArray();
        return $this->imgRep->insert($data);
    }

    public function handelDeleteImages(Question $question, $ids)
    {
        $images = $this->imgRep->getImageDelete($ids, $question);
        $paths = $images->pluck('path')->toArray();
        if (empty($paths)) {
            return;
        }
        $this->fileSer->setPath($this->path)->delete($paths);
        return $this->imgRep->delete($images->pluck('id')->toArray());
    }

    public function handleSaveImages(Question $question, $base64Images)
    {
        $data = [];
        $files = [];
        $newMapping = [];
        foreach ($base64Images as $key => $base64String) {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
                $base64String = substr($base64String, strpos($base64String, ',') + 1);
                $image = new Image();
                $type = strtolower($type[1]);
                $base64String = replaceBase64ImagesWithSpace($base64String);
                $files[] = base64_decode($base64String);
                $image->path = generatePathFile($this->path, $question->id, $type);
                $data[] = $image;
                $newMapping[$key] = $image;
            }
        }
        $images = $this->imgRep->saveMany($question, $data);
        foreach ($files as $index => $file) {
            $this->fileSer->save($data[$index]->path, $file);
        }
        return $newMapping;
    }
}
