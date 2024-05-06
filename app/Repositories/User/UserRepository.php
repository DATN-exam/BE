<?php

namespace App\Repositories\User;

use App\Enums\Classroom\ClassroomStudentStatus;
use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function findUserVerify($token, $userId)
    {
        return $this->model
            ->where('id', $userId)
            ->where('token_verify', $token)
            ->where('status', UserStatus::WAIT_VERIFY)
            ->first();
    }

    public function checkUserExists($email)
    {
        return $this->model
            ->where('email', $email)
            ->first();
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where('id', $filters['id']);
            })
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('first_name', 'like', $filters['name'] . '%')
                    ->orWhere->where('last_name', 'like', $filters['name'] . '%');
            })
            ->when(isset($filters['email']), function ($query) use ($filters) {
                return $query->where('email', 'like', $filters['email'] . '%');
            })
            ->orderBy($filters['sort_column'] ?? 'id', $filters['sort_type'] ?? 'DESC');
    }

    public function paginateStudent($filters)
    {
        return $this->baseList($filters)
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', UserStatus::getValueByKey($filters['status']));
            })
            ->where('role', UserRole::STUDENT)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function exportStudent($filters)
    {
        return $this->baseList($filters)
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', UserStatus::getValueByKey($filters['status']));
            })
            ->where('role', UserRole::STUDENT)
            ->get();
    }

    public function paginateTeacher($filters)
    {
        return $this->baseList($filters)
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', UserStatus::getValueByKey($filters['status']));
            })
            ->where('role', UserRole::TEACHER)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function paginateStudentOfClassroom($filters, $classroom)
    {
        return $classroom->students()
            ->select(
                'users.*',
                'classroom_students.type_join as type_join',
                'classroom_students.status as classroom_status'
            )
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where('id', $filters['id']);
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('classroom_students.status', ClassroomStudentStatus::getValueByKey($filters['status']));
            })
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('first_name', 'like', $filters['name'] . '%')
                    ->orWhere->where('last_name', 'like', $filters['name'] . '%');
            })
            ->when(isset($filters['email']), function ($query) use ($filters) {
                return $query->where('email', 'like', $filters['email'] . '%');
            })
            ->orderBy($filters['sort_column'] ?? 'id', $filters['sort_type'] ?? 'DESC')
            ->paginate($filters['per_page'] ?? 15);
    }
}
