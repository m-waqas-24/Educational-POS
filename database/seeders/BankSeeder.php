<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create([
            'name' => 'Cash',
            'acc_no' => 12345,
        ]);
        Bank::create([
            'name' => 'Easypaisa',
            'acc_no' => 123465,
        ]);
    }
}
