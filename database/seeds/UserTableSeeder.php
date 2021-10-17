<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Evaristo Paulo',
            'email' => 'evaripaulo@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Etelvina Catenda',
            'email' => 'etelvina@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
