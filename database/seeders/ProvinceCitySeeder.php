<?php

namespace Database\Seeders;

use App\Models\ProvinceCity;
use Illuminate\Database\Seeder;

class ProvinceCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProvinceCity::create([
            'province_id' => null,
            'name' => 'Punjab',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Lahore',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Faisalabad',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Rawalpindi',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Gujranwala',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Multan',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Sargodha',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Bahawalpur',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Sialkot',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Sheikhupura',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Rahim Yar Khan',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Jhang',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Dera Ghazi Khan',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Gujrat',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Sahiwal',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Wah Contonment',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Kasur',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Okara',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Chiniot',
        ]);
        ProvinceCity::create([
            'province_id' =>   1,
            'name' => 'Kamoke',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Hafizabad',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Sadiqabad',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Burewala',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Khanewal',
        ]);
        ProvinceCity::create([
            'province_id' => 1,
            'name' => 'Muzaffargarh',
        ]);
    }
}
