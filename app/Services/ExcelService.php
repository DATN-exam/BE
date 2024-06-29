<?php

namespace App\Services;

use App\Exports\Admin\Student\StudentExport;
use App\Exports\RankingExport;
use App\Exports\Teacher\StudentExport as TeacherStudentExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelService
{
    public function __construct()
    {
        //
    }

    public function exportStudent($students)
    {
        return new StudentExport($students);
    }

    public function exportMotos($motos)
    {
        return new RankingExport($motos);
    }

    public function teacherExportStudent($students)
    {
        return new TeacherStudentExport($students);
    }
}
