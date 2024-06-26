<?php

namespace App\Repositories\User;

use App\Enums\Classroom\ClassroomStatus;
use App\Enums\Classroom\ClassroomStudentStatus;
use App\Enums\Question\SetQuestionStatus;
use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\Image;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
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
            ->with('avatar')
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

    public function exportStudentOfClassroom($filters, $classroom)
    {
        return $classroom->students()
            ->select(
                'users.*',
                'classroom_students.type_join as type_join',
                'classroom_students.status as classroom_status',
                'classroom_students.created_at as date_join',
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
            ->get();
    }

    //teacher
    public function analysisTeacher(User $teacher)
    {
        return $this->model->where('id', $teacher->id)
            ->with(['myClassrooms' => function ($query) {
                return $query->select('classrooms.id', 'classrooms.name','classrooms.teacher_id')->withCount('students');
            }])
            ->with(['setQuestion' => function ($query) {
                return $query->select('set_questions.id', 'set_questions.title', 'set_questions.teacher_id')->withCount('questions');
            }])
            ->withCount([
                'myClassrooms',
                'setQuestion',
                'myClassrooms as classroom_active_count' => function ($query) {
                    $query->where('classrooms.status', ClassroomStatus::ACTIVE);
                },
                'setQuestion as set_question_active_count' => function ($query) {
                    $query->where('set_questions.status', SetQuestionStatus::ACTIVE);
                },
            ])
            ->first();
    }

    public function saveAvatar(User $user, Image $image)
    {
        if ($user->avatar) {
            $user->avatar->delete();
        }
        return $user->avatar()->save($image);
    }

    //admin
    public function analysisAdmin()
    {
        $teacher = UserRole::TEACHER->value;
        return $this->model
            ->selectRaw("COUNT(*) as total_users, COUNT(CASE WHEN role = {$teacher} THEN 1 END) as total_teacher")
            ->first()
            ->toArray();
    }

    public function getNumberNewUser()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return $this->model
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
    }

    public function getNumberNewUserMonthly($filters)
    {
        $defaultData = [];
        $year = $filters['year'] ?? Carbon::now()->year;
        for ($month = 1; $month <= 12; $month++) {
            $defaultData[Carbon::create($year, $month, 1)->format('Y-m')] = [
                "month" => Carbon::create($year, $month, 1)->format('Y-m'),
                "number_user" => 0
            ];
        }
        $data = $this->model
            ->selectRaw(DB::raw(
                'DATE_FORMAT(created_at,"%Y-%m") AS month, 
                COUNT(id) as number_user'
            ))
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get()
            ->toArray();
        foreach ($data as $item) {
            $defaultData[$item['month']] = [
                "month" => $item["month"],
                "number_user" => $item["number_user"]
            ];
        }
        return array_values($defaultData);
    }
}
