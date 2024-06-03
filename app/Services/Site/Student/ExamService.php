<?php

namespace App\Services\Site\Student;

use App\Enums\ExamHistory\ExamHistoryType;
use App\Models\Classroom;
use App\Models\Exam;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\ExamAnswer\ExamAnswerRepositoryInterface;
use App\Repositories\ExamHistory\ExamHistoryRepositoryInterface;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Services\BaseService;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        $student = auth('api')->user();
        $examHistory = $this->examHistoryRepo->create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'start_time' => now(),
            'type' => ExamHistoryType::TEST
        ]);
        $questions = $this->questionRepo->getQuestionExamRandom($exam)->toArray();
        $data = [];
        foreach ($questions as $question) {
            $data[] = [
                'exam_history_id' => $examHistory->id,
                'question_id' => $question['id'],
                'score' => $question['score'],
            ];
        }
        $this->examAnserRepo->insert($data);
        $examHistory->load(['exam','examAnswers','examAnswers.question','examAnswers.question.answers']);
        return $examHistory;
    }
}
