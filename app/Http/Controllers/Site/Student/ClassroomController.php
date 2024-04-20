<?php

namespace App\Http\Controllers\Site\Student;

use App\Http\Controllers\BaseApiController;
use App\Http\Controllers\Controller;
use App\Services\Site\Student\ClassroomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClassroomController extends BaseApiController
{
    public function __construct(protected ClassroomService $classroomSer)
    {
    }

    public function join(Request $rq)
    {
        try {
            $this->classroomSer->setRequest($rq)->join();
            return $this->sendResponse([
                'message' => __('alert.classroom.join.success')
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function index(Request $rq)
    {
        $data = $this->classroomSer->setRequest($rq)->paginate();
        dd($data->toArray());   
    }
}
