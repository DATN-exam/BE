<?php

namespace App\Services;

use App\Repositories\Image\ImageRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileService
{
    public function __construct(protected ImageRepositoryInterface $imgRep)
    {
        //
    }

    protected $path = '';

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function save($path, $file)
    {
        return Storage::disk(config('filesystems.default'))->put($path, $file);
    }

    public function delete($paths)
    {
        foreach ($paths as $path) {
            $file = Storage::disk(config('filesystems.default'))->get($path);
            Storage::disk(config('filesystems.trash'))->put($path, $file);
            // try {
            // } catch (Throwable $e) {
            //     Log::error($e);
            // }
        }
        return Storage::disk(config('filesystems.default'))->delete($paths);
    }
}
