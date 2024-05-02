<?php

namespace App\Exports\Admin\Student;

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
            'id',
            'email',
            'status',
            'first_name',
            'last_name',
            'dob',
            'description',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->email,
            $row->status->name,
            $row->first_name,
            $row->last_name,
            $row->dob,
            $row->description,
        ];
    }
}
