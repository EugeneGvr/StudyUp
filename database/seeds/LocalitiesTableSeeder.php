<?php

use App\Locality;
use Illuminate\Database\Seeder;

class LocalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Locality::create([
        'code' => '001',
        'name' => 'Eugene',
        'parent_code' => '000',
        'center' => true,
        'type' => 'С',
    ]);
    }
}
