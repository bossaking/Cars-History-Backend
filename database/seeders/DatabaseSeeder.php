<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Owner',
            'surname' => 'Owner',
            'email' => 'owner@carshistory.com',
            'password' => Hash::make('Admin123@'),
            'active' => true
        ]);

        Role::factory()->create([
            'name' => 'OWNER'
        ]);
        Role::factory()->create([
            'name' => 'ADMIN'
        ]);
        Role::factory()->create([
            'name' => 'MECHANIC'
        ]);
        Role::factory()->create([
            'name' => 'USER'
        ]);

        DB::
        table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => 1
            ], [
                'user_id' => 1,
                'role_id' => 2
            ],
            [
                'user_id' => 1,
                'role_id' => 3
            ], [
                'user_id' => 1,
                'role_id' => 4
            ]]);

    }
}
