<?php

namespace App\Services;

use App\Exports\Admin\Student\StudentExport;
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
}
