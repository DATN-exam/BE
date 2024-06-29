<?php

namespace App\Services\Admin;

use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\SetQuestion\SetQuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;

class AnalysisService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected ExamRepositoryInterface $examRepo,
        protected SetQuestionRepositoryInterface $setQuestionRepo,
        protected ClassroomRepositoryInterface $classroomRepo
    ) {
        //
    }

    public function analysis()
    {
        $dataUser = $this->userRepo->analysisAdmin();
        $numberNewUser = $this->userRepo->getNumberNewUser();
        $numberExam = $this->examRepo->getNumberExam();
        $numberSetQuestion = $this->setQuestionRepo->getNumberSetQuestion();
        $numberClassroom = $this->classroomRepo->getNumberClassroom();
        $rs = [
            'total_users' => $dataUser['total_users'],
            'number_new_user' => $numberNewUser,
            'total_teacher' => $dataUser['total_teacher'],
            'number_exams' => $numberExam,
            'number_set_questions' => $numberSetQuestion,
            'number_classroom' => $numberClassroom,
        ];
        return $rs;
    }

    public function newUserMonthly()
    {
        return $this->userRepo->getNumberNewUserMonthly($this->data);
    }

    public function newClassroomMonthly()
    {
        return $this->classroomRepo->getNumberClassroomMonthly($this->data);
    }
}
