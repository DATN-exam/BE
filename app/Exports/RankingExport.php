<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RankingExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomChunkSize
{
    public function __construct(protected $rankings)
    {
    }

    public function collection()
    {
        return $this->rankings;
    }

    public function headings(): array
    {
        return [
            'Họ và tên',
            'Email',
            'Thời gian bắt đầu',
            'Thời gian nộp bài',
            'Thời gian làm bài',
            // 'Điểm trên thang 10',
            'Tổng điểm ',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($row): array
    {
        return [
            "{$row->student->first_name} {$row->student->last_name}",
            $row->student->email,
            $row->start_time,
            $row->submit_time,
            gmdate('H:i:s', $row->time_taken),
            // $row->total_score,
            $row->total_score,
        ];
    }
}
