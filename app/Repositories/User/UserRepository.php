<?php

namespace App\Repositories\User;

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
                return $query->where('firts_name', 'like', $filters['name'] . '%')
                    ->orWhere->where('last_name', 'like', $filters['name'] . '%');
            })
            ->when(isset($filters['email']), function ($query) use ($filters) {
                return $query->where('email', 'like', $filters['email'] . '%');
            })
            ->when(isset($filters['sort_column']), function ($query) use ($filters) {
                return $query->orderBy($filters['sort_column'], $filters['sort_type'] ?? 'ASC');
            });
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

    public function paginateTeacher($filters)
    {
        return $this->baseList($filters)
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', UserStatus::getValueByKey($filters['status']));
            })
            ->where('role', UserRole::TEACHER)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function paginateStudentOfClassroom($filters, $classroomId)
    {
        return $this->baseList($filters)
            ->whereHas('classroomStudents', function ($query) use ($classroomId) {
                return $query->where('classroom_id', $classroomId);
            })
            ->with('classroomStudents')
            ->paginate($filters['per_page'] ?? 15);
    }
}
