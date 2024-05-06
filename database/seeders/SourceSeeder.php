<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Source;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Source::create([
            'name' => 'Facebook',
        ]);
        Source::create([
            'name' => 'Instagram',
        ]);
        Source::create([
            'name' => 'Youtube',
        ]);
        Source::create([
            'name' => 'LinkedIn',
        ]);
        Source::create([
            'name' => 'Other',
        ]);

        Batch::create([
            'course_id' => 4,
            'number' => 1,
            'starting_date' => '2024-02-05',
            'adm_closing_date' => '2024-02-12',
            'is_active' => 1,
            'is_open' => 1,
        ]);

        User::create([
            'name' => 'Ahmad',
            'email' => 'ahmad@ahmad.com',
            'mob' => 0000000,
            'password' => Hash::make('password'),
            'type' => 'csr',
       ]);

    }
}
