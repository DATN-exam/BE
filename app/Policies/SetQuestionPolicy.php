<?php

namespace App\Policies;

use App\Models\SetQuestion;
use App\Models\User;

class SetQuestionPolicy
{
    public function update(User $user, SetQuestion $setQuestion)
    {
        return $user->id === $setQuestion->teacher_id;
    }
}
