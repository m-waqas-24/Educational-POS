<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'name' => 'Video Editing & Production',
            'fee' => 20000,
            'duration' => '2 months',
        ]);
        Course::create([
            'name' => 'Diploma in Cyber Security',
            'fee' => 120000,
            'duration' => '1 year',
        ]);
        Course::create([
            'name' => 'Data Science',
            'fee' => 35000,
            'duration' => '4 months',
        ]);
        Course::create([
            'name' => 'Mern Stack Web Development',
            'fee' => 35000,
            'duration' => '4 months',
        ]);
        Course::create([
            'name' => 'Digital Media Marketing Advance',
            'fee' => 25000,
            'duration' => '3 months',
        ]);
        Course::create([
            'name' => 'Full Stack Graphic Designing',
            'fee' => 25000,
            'duration' => '3 months',
        ]);
        Course::create([
            'name' => 'Amazon Virtual Assistant',
            'fee' => 25000,
            'duration' => '2 months',
        ]);
        Course::create([
            'name' => 'Cyber Security',
            'fee' => 40000,
            'duration' => '3 months',
        ]);
    }
}
