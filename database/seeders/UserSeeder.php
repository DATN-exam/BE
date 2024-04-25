<?php

namespace Database\Seeders;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\Answer;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\SetQuestion;
use App\Models\TeacherRegistration;
use App\Models\User;
use Database\Factories\AnswerFactory;
use Database\Factories\QuestionFactory;
use Database\Factories\SetQuestionFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->state([
            'email' => 'admin@gmail.com',
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE
        ])->create();

        User::factory(1)
            ->state([
                'email' => 'teacher@gmail.com',
                'role' => UserRole::TEACHER,
                'status' => UserStatus::ACTIVE
            ])
            ->has(
                Classroom::factory()
                    ->count(3)
                    ->state(function (array $attributes, User $user) {
                        return ['teacher_id' => $user->id];
                    })
            )
            ->has(
                SetQuestion::factory()
                    ->count(5)
                    ->state(function (array $attributes, User $user) {
                        return ['teacher_id' => $user->id];
                    })
                    ->has(
                        Question::factory()
                            ->count(50)
                            ->state(function (array $attributes, SetQuestion $setQuestion) {
                                return ['set_question_id' => $setQuestion->id];
                            })
                            ->has(
                                Answer::factory()
                                    ->count(3)
                                    ->state(function (array $attributes, Question $question) {
                                        return ['question_id' => $question->id];
                                    })
                            )
                            ->has(
                                Answer::factory()
                                    ->count(1)
                                    ->state(function (array $attributes, Question $question) {
                                        return [
                                            'question_id' => $question->id,
                                            'is_correct' => true,
                                            'answer' => 'Đúng'
                                        ];;
                                    })
                            )
                    )
                    ->has(
                        Question::factory()
                            ->count(50)
                            ->state(function (array $attributes, SetQuestion $setQuestion) {
                                return ['set_question_id' => $setQuestion->id, 'is_testing' => true];
                            })
                            ->has(
                                Answer::factory()
                                    ->count(3)
                                    ->state(function (array $attributes, Question $question) {
                                        return ['question_id' => $question->id];
                                    })
                            )
                            ->has(
                                Answer::factory()
                                    ->count(1)
                                    ->state(function (array $attributes, Question $question) {
                                        return [
                                            'question_id' => $question->id,
                                            'is_correct' => true,
                                            'answer' => 'Đúng'
                                        ];
                                    })
                            )
                    )
            )
            ->create();

        User::factory(1)->state([
            'email' => 'student@gmail.com',
            'role' => UserRole::STUDENT,
            'status' => UserStatus::ACTIVE
        ])->create();

        $this->createClassroom(10, 1, rand(1, 5), rand(30, 50));
        $this->createClassroom(10, 1, rand(1, 5), rand(30, 50));
        $this->createClassroom(10, 1, rand(1, 5), rand(30, 50));
        $this->createClassroom(10, 1, rand(1, 5), rand(30, 50));

        User::factory(100)->state([
            'role' => UserRole::TEACHER,
        ])->create();

        User::factory(100)
            ->state([
                'status' => UserStatus::ACTIVE,
            ])
            ->has(
                TeacherRegistration::factory()
                    ->count(1)
                    ->state(function (array $attributes, User $user) {
                        return ['user_id' => $user->id];
                    })
            )->create();
        User::factory(1000)->create();
    }

    private function createClassroom($quantity, $quantityClassroom, $quantitySetQuestion, $quantityQuestion)
    {
        User::factory($quantity)
            ->state([
                'first_name' => 'Teacher',
                'role' => UserRole::TEACHER
            ])
            ->has(
                Classroom::factory()
                    ->count($quantityClassroom)
                    ->state(function (array $attributes, User $user) {
                        return ['teacher_id' => $user->id];
                    })
            )
            ->has(
                SetQuestion::factory()
                    ->count($quantitySetQuestion)
                    ->state(function (array $attributes, User $user) {
                        return ['teacher_id' => $user->id];
                    })
                    ->has(
                        Question::factory()
                            ->count($quantityQuestion)
                            ->state(function (array $attributes, SetQuestion $setQuestion) {
                                return ['set_question_id' => $setQuestion->id];
                            })
                            ->has(
                                Answer::factory()
                                    ->count(3)
                                    ->state(function (array $attributes, Question $question) {
                                        return ['question_id' => $question->id];
                                    })
                            )
                            ->has(
                                Answer::factory()
                                    ->count(1)
                                    ->state(function (array $attributes, Question $question) {
                                        return [
                                            'question_id' => $question->id,
                                            'is_correct' => true,
                                            'answer' => 'Đúng'
                                        ];;
                                    })
                            )
                    )
                    ->has(
                        Question::factory()
                            ->count($quantityQuestion)
                            ->state(function (array $attributes, SetQuestion $setQuestion) {
                                return ['set_question_id' => $setQuestion->id, 'is_testing' => true];
                            })
                            ->has(
                                Answer::factory()
                                    ->count(3)
                                    ->state(function (array $attributes, Question $question) {
                                        return ['question_id' => $question->id];
                                    })
                            )
                            ->has(
                                Answer::factory()
                                    ->count(1)
                                    ->state(function (array $attributes, Question $question) {
                                        return [
                                            'question_id' => $question->id,
                                            'is_correct' => true,
                                            'answer' => 'Đúng'
                                        ];
                                    })
                            )
                    )
            )
            ->create();
    }
}
