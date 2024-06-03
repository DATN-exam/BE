<?php

namespace App\Repositories\ExamHistory;

use App\Models\ExamHistory;
use App\Repositories\BaseRepository;

class ExamHistoryRepository extends BaseRepository implements ExamHistoryRepositoryInterface
{
    public function getModel()
    {
        return ExamHistory::class;
    }
}
