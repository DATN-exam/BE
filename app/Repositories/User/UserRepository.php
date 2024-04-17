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
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('firts_name', 'like', $filters['name'] . '%')
                    ->orWhere->where('last_name', 'like', $filters['name'] . '%');
            })
            ->when(isset($filters['email']), function ($query) use ($filters) {
                return $query->where('email', 'like', $filters['email'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', UserStatus::getValueByKey($filters['status']));
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->modelSort($filters['sort']);
            });
    }

    public function paginateStudent($filters)
    {
        return $this->baseList($filters)
            ->where('role', UserRole::STUDENT)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function paginateTeacher($filters)
    {
        return $this->baseList($filters)
            ->where('role', UserRole::TEACHER)
            ->paginate($filters['per_page'] ?? 15);
    }
}
