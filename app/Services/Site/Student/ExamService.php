<?php

namespace App\Services\Site\Student;

use App\Enums\ExamHistory\ExamHistoryStatus;
use App\Enums\ExamHistory\ExamHistoryType;
use App\Enums\Question\QuestionType;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamAnwser;
use App\Models\ExamHistory;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\ExamAnswer\ExamAnswerRepositoryInterface;
use App\Repositories\ExamHistory\ExamHistoryRepositoryInterface;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamService extends BaseService
{
    public function __construct(
        protected ExamRepositoryInterface $examRepo,
        protected ExamHistoryRepositoryInterface $examHistoryRepo,
        protected ExamAnswerRepositoryInterface $examAnserRepo,
        protected QuestionRepositoryInterface $questionRepo
    ) {
        //
    }

    public function all(Classroom $classroom)
    {
        return $this->examRepo->allOfClassroom($classroom);
    }

    public function start(Exam $exam)
    {
        $student = auth('api')->user();
        $current = $this->examHistoryRepo->getCurrentTest($student, $exam);
        if ($current) {
            return throw new \Exception(__('alert.classroom.start.exist'));
        }
        DB::beginTransaction();
        $examHistory = $this->examHistoryRepo->create([
            'exam_id' => $exam->id,
            'status' => ExamHistoryStatus::ACTIVE,
            'student_id' => $student->id,
            'start_time' => Carbon::now(),
            'type' => ExamHistoryType::TEST
        ]);
        $questions = $this->questionRepo->getQuestionExamRandom($exam)->toArray();
        $data = [];
        foreach ($questions as $question) {
            $data[] = [
                'exam_history_id' => $examHistory->id,
                'question_id' => $question['id'],
                'score' => $question['score'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->examAnserRepo->insert($data);
        $examHistory->refresh();
        $examHistory->load(['exam']);
        DB::commit();
        return $examHistory;
    }

    public function getCurrent(Exam $exam)
    {
        $student = auth('api')->user();
        return $this->examHistoryRepo->getCurrentTest($student, $exam);
    }

    public function changeAnwser(ExamAnwser $examAnwser)
    {
        $examAnwser->load(['question', 'question.answers']);
        if ($examAnwser->question->type === QuestionType::MULTIPLE) {
            if ($examAnwser->question->answers->pluck('id')->search($this->data['answer_id']) === false) {
                return throw new \Exception(__('alert.answer.not_exist'));
            }
        }
        return $this->examAnserRepo->update($examAnwser, $this->data);
    }

    public function submit(ExamHistory $examHistory)
    {
        DB::beginTransaction();
        $examHistory->load(['examAnswers', 'examAnswers.question', 'examAnswers.question.answers' => function ($query) {
            return $query->where('is_correct', true)->orWhereNull('is_correct');
        }]);

        $examHistory->examAnswers->each(function ($item) {
            if ($item->question->type === QuestionType::MULTIPLE) {
                if ($item->answer_id === $item->question->answers->get(0)->id) {
                    $item->is_correct = true;
                } else {
                    $item->is_correct = false;
                }
            } else {
                $isCorrect = $item->question->answers->search(function ($answer) use ($item) {
                    return Str::lower($answer->answer) === Str::lower($item->answer_text);
                });
                if ($isCorrect === false) {
                    $item->is_correct = false;
                } else {
                    $item->is_correct = true;
                }
            }
            $item->save();
        });
        $submitTime = getTimeSubmit($examHistory->start_time,$examHistory->exam->working_time);
        $totalScore = $examHistory->examAnswers()->where('is_correct', true)->sum('score');
        $examHistory->total_score = $totalScore;
        $examHistory->is_submit = true;
        $examHistory->submit_time = $submitTime;
        $examHistory->save();
        DB::commit();
        return $examHistory;
    }
}
