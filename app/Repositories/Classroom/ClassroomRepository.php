<?php

namespace App\Repositories\Classroom;

use App\Enums\Classroom\ClassroomStatus;
use App\Enums\Classroom\ClassroomStudentStatus;
use App\Models\Classroom;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClassroomRepository extends BaseRepository implements ClassroomRepositoryInterface
{
    public function getModel()
    {
        return Classroom::class;
    }

    public function paginateOfTeacher($filters, $techerId)
    {
        return $this->baseList($filters)
            ->select('classrooms.*', DB::raw('COUNT(classroom_students.student_id) as quantity_student'))
            ->leftJoin('classroom_students', 'classroom_students.classroom_id', '=', 'classrooms.id')
            ->groupBy('classrooms.id')
            ->when(isset($filters['sort_column']) && $filters['sort_column'] == 'count_student', function ($query) use ($filters) {
                return $query->orderBy('quantity_student', $filters['sort_type'] ?? 'ASC');
            })
            ->where('classrooms.teacher_id', $techerId)
            ->paginate($filters['per_page'] ?? 10);
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where('id', $filters['id']);
            })
            // ->when(isset($filters['name_teacher']), function ($query) use ($filters) {
            //     return $query->whereHas('teacher', function ($query) use ($filters) {
            //         return $query->where('first_name', 'like', $filters['name_teacher'] . '%')
            //             ->orWhere->where('last_name', 'like', $filters['name_teacher'] . '%');
            //     });
            // })
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', ClassroomStatus::getValueByKey($filters['status']));
            })
            ->when(isset($filters['sort_column']) && $filters['sort_column'] != 'count_student', function ($query) use ($filters) {
                return $query->orderBy($filters['sort_column'], $filters['sort_type'] ?? 'ASC');
            });
    }

    public function paginateStudent($filters, $studentId)
    {
        return $this->baseList($filters)
            ->whereHas('students', function ($query) use ($studentId) {
                return $query->where('classroom_students.student_id', $studentId)
                    ->where('classroom_students.status', ClassroomStudentStatus::ACTIVE);
            })
            ->paginate($filters['per_page'] ?? 10);
    }

    public function getNumberClassroomMonthly($filters)
    {
        $defaultData = [];
        $year = $filters['year'] ?? Carbon::now()->year;
        for ($month = 1; $month <= 12; $month++) {
            $defaultData[Carbon::create($year, $month, 1)->format('Y-m')] = [
                "month" => Carbon::create($year, $month, 1)->format('Y-m'),
                "number_classroom" => 0
            ];
        }
        $data = $this->model
            ->selectRaw(DB::raw(
                'DATE_FORMAT(created_at,"%Y-%m") AS month, 
                COUNT(id) as number_classroom'
            ))
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get()
            ->toArray();
        foreach ($data as $item) {
            $defaultData[$item['month']] = [
                "month" => $item["month"],
                "number_classroom" => $item["number_classroom"]
            ];
        }
        return array_values($defaultData);
    }

    public function getNumberClassroom()
    {
        return $this->model->count();
    }
}
