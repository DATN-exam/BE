<?php

namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

abstract class BaseService
{
    protected $data;

    public function setRequest(FormRequest $request)
    {
        $this->data = $request->validated();
        return $this;
    }
}
