<?php

namespace App\Exports\Teacher;

use App\Enums\Classroom\ClassroomStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomChunkSize
{
    public function __construct(protected $students)
    {
        //
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'Email',
            'Họ và tên',
            'Tên',
            'Ngày sinh',
            'Trạng thái',
            'Ngày tham gia lớp học',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($row): array
    {
        return [
            $row->email,
            $row->first_name,
            $row->last_name,
            $row->dob,
            ClassroomStatus::getKeyByValue($row->classroom_status),
            $row->date_join,
        ];
    }
}
