<?php

namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BaseService
{
    protected $data;

    public function setRequestValidated(FormRequest $request)
    {
        $this->data = $request->validated();
        return $this;
    }

    public function setRequest(Request $rq)
    {
        $this->data = collect($rq->all())->filter(function ($item) {
            return $item !== null && $item !== '';
        })->toArray();
        return $this;
    }
}
