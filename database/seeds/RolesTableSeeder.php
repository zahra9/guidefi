<?php

use Illuminate\Database\Seeder;
use jeremykenedy\LaravelRoles\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Roles
         *
         */
        if (Role::where('name', '=', 'Admin')->first() === null) {
            $adminRole = Role::create([
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin Role',
                'level'       => 5,
            ]);
        }

        if (Role::where('name', '=', 'User')->first() === null) {
            $userRole = Role::create([
                'name'        => 'User',
                'slug'        => 'user',
                'description' => 'User Role',
                'level'       => 1,
            ]);
        }

        if (Role::where('name', '=', 'Client')->first() === null) {
            $userRole = Role::create([
                'name'        => 'Client',
                'slug'        => 'client',
                'description' => 'client Role',
                'level'       => 1,
            ]);
        }

        if (Role::where('name', '=', 'Therapist')->first() === null) {
            $userRole = Role::create([
                'name'        => 'Therapist',
                'slug'        => 'therapist',
                'description' => 'therapist Role',
                'level'       => 1,
            ]);
        }
    }
}
