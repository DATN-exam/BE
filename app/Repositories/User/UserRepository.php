<?php

namespace App\Repositories\User;

use App\Enums\Classroom\ClassroomStatus;
use App\Enums\Classroom\ClassroomStudentStatus;
use App\Enums\Question\SetQuestionStatus;
use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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

    //teacher
    public function analysisTeacher(User $teacher)
    {
        return $this->model->where('id', $teacher->id)
            ->with(['classrooms' => function ($query) {
                return $query->select('classrooms.id', 'classrooms.name')->withCount('students');
            }])
            ->with(['setQuestion' => function ($query) {
                return $query->select('set_questions.id', 'set_questions.title', 'set_questions.teacher_id')->withCount('questions');
            }])
            ->withCount([
                'classrooms',
                'setQuestion',
                'classrooms as classroom_active_count' => function ($query) {
                    $query->where('classrooms.status', ClassroomStatus::ACTIVE);
                },
                'setQuestion as set_question_active_count' => function ($query) {
                    $query->where('set_questions.status', SetQuestionStatus::ACTIVE);
                },
            ])
            ->first();
    }
}
