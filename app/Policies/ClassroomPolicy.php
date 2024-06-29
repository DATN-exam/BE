<?php

namespace App\Policies;

use App\Enums\Classroom\ClassroomStatus;
use App\Enums\Classroom\ClassroomStudentStatus;
use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    public function teacherUpdate(User $teacher, Classroom $classroom)
    {
        return $teacher->id === $classroom->teacher_id && $teacher->status !== ClassroomStatus::ADMIN_BLOCK;
    }

    public function teacherManageClassroom(User $teacher, Classroom $classroom)
    {
        return $teacher->id === $classroom->teacher_id && $teacher->status !== ClassroomStatus::ADMIN_BLOCK;
    }

    public function classroomManageStudent(User $teacher, Classroom $classroom, User $student)
    {
        return $classroom->classroomStudents()->where('student_id', $student->id)->exists();
    }

    public function studentShow(User $student, Classroom $classroom)
    {
        $exists = $classroom
            ->classroomStudents()
            ->where('student_id', $student->id)
            // ->where('status', ClassroomStudentStatus::ACTIVE)
            ->first();
        if (!$exists) {
            abort(404, 'Lớp học không tồn tại');
        }
        if ($exists->status !== ClassroomStudentStatus::ACTIVE) {
            abort(403, 'Bạn đã bị khóa khỏi lớp học');
        }
        return true;
    }
}
