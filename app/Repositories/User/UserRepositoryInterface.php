<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findUserVerify($token, $userId);

    public function checkUserExists($email);

    public function paginateStudent($filters);

    public function paginateTeacher($filters);

    public function paginateStudentOfClassroom($filters, $classroom);

    public function exportStudent($filters);
}
