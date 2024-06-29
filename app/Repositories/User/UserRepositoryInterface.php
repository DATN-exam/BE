<?php

namespace App\Repositories\User;

use App\Models\Image;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findUserVerify($token, $userId);

    public function checkUserExists($email);

    public function paginateStudent($filters);

    public function paginateTeacher($filters);

    public function paginateStudentOfClassroom($filters, $classroom);

    public function exportStudentOfClassroom($filters, $classroom);

    public function exportStudent($filters);

    public function analysisTeacher(User $teacher);

    public function saveAvatar(User $user, Image $image);

    public function analysisAdmin();

    public function getNumberNewUser();

    public function getNumberNewUserMonthly($filters);
}
