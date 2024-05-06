<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Qualification::create([
            'name' => 'Matric',
            'parent_id' => null,
        ]);
        Qualification::create([
            'name' => 'Intermediate',
            'parent_id' => null,
        ]);
        Qualification::create([
            'name' => 'O/A Levels',
            'parent_id' => null,
        ]);
        Qualification::create([
            'name' => 'UnderGraduate',
            'parent_id' => null,
        ]);
        Qualification::create([
            'name' => 'Graduate',
            'parent_id' => null,
        ]);
        Qualification::create([
            'name' => 'Post Graduate',
            'parent_id' => null,
        ]);
        Qualification::create([
            'name' => 'Arts',
            'parent_id' => 1,
        ]);
        Qualification::create([
            'name' => 'Science',
            'parent_id' => 1,
        ]);
        Qualification::create([
            'name' => 'F.Sc (Pre-Engineering)',
            'parent_id' => 2,
        ]);
        Qualification::create([
            'name' => 'F.Sc (Pre-Medical)',
            'parent_id' => 2,
        ]);
        Qualification::create([
            'name' => 'ICS',
            'parent_id' => 2,
        ]);
        Qualification::create([
            'name' => 'I.Com',
            'parent_id' => 2,
        ]);
        Qualification::create([
            'name' => 'F.A',
            'parent_id' => 2,
        ]);
        Qualification::create([
            'name' => 'F.A General Science',
            'parent_id' => 2,
        ]);
        Qualification::create([
            'name' => 'O Levels',
            'parent_id' => 3,
        ]);
        Qualification::create([
            'name' => 'A Levels',
            'parent_id' => 3,
        ]);
        Qualification::create([
            'name' => 'Computer Studies',
            'parent_id' => 4,
        ]);
        Qualification::create([
            'name' => 'Engineering',
            'parent_id' => 4,
        ]);
        Qualification::create([
            'name' => 'Accounting and Finance',
            'parent_id' => 4,
        ]);
        Qualification::create([
            'name' => 'Medical',
            'parent_id' => 4,   
        ]);
        Qualification::create([
            'name' => 'Bussiness and Management',
            'parent_id' => 4,   
        ]);
        Qualification::create([
            'name' => 'Humanities',
            'parent_id' => 4,   
        ]);
        Qualification::create([
            'name' => 'Arts & Design',
            'parent_id' => 4,   
        ]);
        Qualification::create([
            'name' => 'Law',
            'parent_id' => 4,   
        ]);
        Qualification::create([
            'name' => 'Media Studies',
            'parent_id' => 4,
        ]);
    }
}
