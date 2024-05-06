<?php

namespace Database\Seeders;

use App\Models\Admin\CsrActionStatus;
use Illuminate\Database\Seeder;

class CsrActionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CsrActionStatus::create(['name' => "Interested", 'icon' => 'fa fa-thumbs-up']);
        CsrActionStatus::create(['name' => "Not Interested", 'icon' => 'fa fa-thumbs-down']);
        CsrActionStatus::create(['name' => "Busy", 'icon' => 'mdi mdi-cellphone-message']);
        CsrActionStatus::create(['name' => "Not Responding", 'icon' => 'mdi mdi-cellphone-arrow-down']);
        CsrActionStatus::create(['name' => "Switchoff", 'icon' => 'mdi mdi-cellphone-off']);
        CsrActionStatus::create(['name' => "Whatsapp", 'icon' => 'mdi mdi-whatsapp']);
        CsrActionStatus::create(['name' => "Already Paid", 'icon' => 'mdi mdi-file-check']);
    }
}
