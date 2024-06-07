<?php

namespace Database\Seeders;

use App\Models\Admin\StudentLectureAttendanceStatus;
use Illuminate\Database\Seeder;

class StudentLectureAttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentLectureAttendanceStatus::create([
            'name' => 'Present',
        ]);
        StudentLectureAttendanceStatus::create([
            'name' => 'Absent',
        ]);
        StudentLectureAttendanceStatus::create([
            'name' => 'Leave',
        ]);
    }
}
