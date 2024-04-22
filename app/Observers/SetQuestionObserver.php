<?php

namespace App\Observers;

use App\Enums\Question\SetQuestionStatus;
use App\Models\SetQuestion;

class SetQuestionObserver
{
    public function creating(SetQuestion $setQuestion)
    {
        $setQuestion->status = $setQuestion->status ?? SetQuestionStatus::ACTIVE;
    }
}
