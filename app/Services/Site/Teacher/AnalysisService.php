<?php

namespace App\Services\Site\Teacher;

use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;

class AnalysisService extends BaseService
{
    public function __construct(protected UserRepositoryInterface $teacherRepo)
    {
        //
    }

    public function analysis()
    {
        $teacher = auth('api')->user();
        return $this->teacherRepo->analysisTeacher($teacher);
    }
}
