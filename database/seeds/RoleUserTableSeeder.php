<?php

use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleUser::create([
            'role_id' => 2,
            'user_id' => 1,
        ]);

        RoleUser::create([
            'role_id' => 4,
            'user_id' => 2,
        ]);
    }
}
