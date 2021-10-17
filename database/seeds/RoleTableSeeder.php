<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'type' => 'admin',
        ]);

        Role::create([
            'type' => 'secretary',
        ]);

        Role::create([
            'type' => 'trainer',
        ]);

        Role::create([
            'type' => 'trainee',
        ]);
    }
}
