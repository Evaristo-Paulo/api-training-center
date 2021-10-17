<?php

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::create([
            'type' => 'Male',
        ]);

        Gender::create([
            'type' => 'Female',
        ]);

        Gender::create([
            'type' => 'Others',
        ]);
    }
}
