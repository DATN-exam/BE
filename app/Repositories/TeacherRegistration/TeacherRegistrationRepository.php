<?php

namespace App\Repositories\TeacherRegistration;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use App\Models\TeacherRegistration;
use App\Repositories\BaseRepository;

class TeacherRegistrationRepository extends BaseRepository implements TeacherRegistrationRepositoryInterface
{
    public function getModel()
    {
        return TeacherRegistration::class;
    }

    public function checkHasBeenRegistration($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereIn('status', [
                TeacherRegistrationStatus::ACCEPT, TeacherRegistrationStatus::WAIT
            ])
            ->exists();
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where('id', $filters['id']);
            })
            ->when(isset($filters['username']), function ($query) use ($filters) {
                return $query->whereHas('user', function ($query) use ($filters) {
                    return $query->where('users.first_name', 'like', $filters['username'] . '%')
                        ->orWhere('users.last_name', 'like', $filters['username'] . '%');
                });
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where(
                    'status',
                    TeacherRegistrationStatus::getValueByKey($filters['status'])
                );
            })
            ->orderBy($filters['sort_column'] ?? 'created_at', $filters['sort_type'] ?? 'ASC')
            ->with(['user', 'employeeCofirm']);
    }

    public function paginate($filters)
    {
        $VALUE_DEFAULT_PER_PAGE = 10;
        return $this->baseList($filters)
            ->paginate($filters['per_page'] ?? $VALUE_DEFAULT_PER_PAGE);
    }
}
