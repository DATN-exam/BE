<?php

namespace App\Http\Controllers\Site\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Teacher\ClassroomRequest;
use App\Services\Site\Teacher\ClassroomService;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    
    public function __construct(protected ClassroomService $classroomSer)
    {
        //
    }

    public function index(Request $rq)
    {
        $data = $this->classroomSer->setRequest($rq)->paginate();
        dd($data);
    }

    public function store(ClassroomRequest $rq)
    {
        $data = $this->classroomSer->setRequestValidated($rq)->store();
        dd($data);
    }
}
