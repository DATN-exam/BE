<?php

namespace Database\Seeders;

use App\Enums\Classroom\ClassroomStatus;
use App\Enums\User\UserRole;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = Classroom::all();
        $data = collect([]);
        $now = now();
        foreach ($classrooms as $classroom) {
            $randomQuatity = rand(30, 50);
            $users = User::where('role', UserRole::STUDENT)->inRandomOrder()->limit($randomQuatity)->get();
            $data  = $data->concat($users->map(function ($user) use ($classroom, $now) {
                return [
                    'student_id' => $user->id,
                    'classroom_id' => $classroom->id,
                    'status' => ClassroomStatus::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }));
        }
        DB::table('classroom_students')->insert($data->toArray());
        // ClassroomStudent::insert($data->toArray());
    }
}
