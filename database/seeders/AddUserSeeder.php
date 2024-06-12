<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'type' => 'admin',
        ]);
        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@superadmin.com',
            'password' => Hash::make('password'),
            'type' => 'superadmin',
        ]);
        User::create([
            'name' => 'accountant',
            'email' => 'accountant@accountant.com',
            'password' => Hash::make('password'),
            'type' => 'accountant',
        ]);
        
        User::create([
            'name' => 'instructor',
            'email' => 'instructor@instructor.com',
            'password' => Hash::make('password'),
            'type' => 'instructor',
        ]);
    }
}
