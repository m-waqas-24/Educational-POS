<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AddUserSeeder::class);
        $this->call(CsrActionStatusSeeder::class);
        $this->call(SourceSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(ProvinceCitySeeder::class);
        $this->call(QualificationSeeder::class);
        $this->call(StudentLectureAttendanceStatusSeeder::class);
        $this->call(TaskStatusSeeder::class);
    }
}
