<?php

namespace App\Repositories\Exam;

use App\Enums\ExamHistory\ExamHistoryType;
use App\Models\Classroom;
use App\Models\Exam;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ExamRepository extends BaseRepository implements ExamRepositoryInterface
{
    public function getModel()
    {
        return Exam::class;
    }

    public function getList(Classroom $classroom, $filters)
    {
        return $this->model
            ->where('classroom_id', $classroom->id)
            ->paginate($filters['per_page'] ?? 15);;
    }

    public function allOfClassroom(Classroom $classroom)
    {
        return $this->model
            ->where('classroom_id', $classroom->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTop(Exam $exam)
    {
        return $exam->examHistories()
            ->select([
                '*',
                DB::raw('TIMESTAMPDIFF(SECOND, start_time, submit_time) AS time_taken')
            ])
            ->where('is_submit', true)
            ->where('type', ExamHistoryType::TEST)
            ->orderBy('total_score', 'desc')
            ->orderBy('time_taken')
            ->with('student')
            ->get();
    }

    public function analysis(Exam $exam)
    {
        $statisis = $exam->examHistories()
            ->select(
                DB::raw('COUNT(DISTINCT student_id) as number_student_join'),
                DB::raw('AVG(total_score) as average_score'),
                DB::raw('MAX(total_score) as max_score'),
                DB::raw("TIME_FORMAT(SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, start_time, submit_time))), '%H:%i:%s') as average_time_taken")
            )
            ->where('is_submit', true)
            ->where('type', ExamHistoryType::TEST)
            ->first()
            ->toArray();
        $totalStudent = $exam->classroom->students()->count();
        return [...$statisis, "total_student" => $totalStudent];
    }

    public function getExamsStartNotification()
    {
        $now = now();
        return $this->model
            ->where('notification_start', false)
            ->where('start_date', '<', $now)
            ->with(['classroom', 'classroom.students'])
            ->get();
    }

    public function getExamsEndNotification()
    {
        $now = now();
        return $this->model
            ->where('notification_end', false)
            ->where('end_date', '<', $now)
            ->with(['classroom', 'classroom.students'])
            ->get();
    }
}
