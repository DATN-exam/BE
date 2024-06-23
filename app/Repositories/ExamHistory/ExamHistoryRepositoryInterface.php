<?php

namespace App\Repositories\ExamHistory;

use App\Models\Exam;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface ExamHistoryRepositoryInterface extends RepositoryInterface
{
    public function getCurrentTest(User $student, Exam $exam);

    public function getExamHistoryExpried();

    public function getCurrentExperiment(User $student, Exam $exam);
}
