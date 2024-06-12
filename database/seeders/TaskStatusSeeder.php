<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskStatus::create([
            'name' => 'Before Batch',
        ]);
        TaskStatus::create([
            'name' => 'Mid of Batch',
        ]);
        TaskStatus::create([
            'name' => 'After Batch',
        ]);
    }
}
