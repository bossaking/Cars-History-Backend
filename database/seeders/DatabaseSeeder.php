<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CarManufacturer;
use App\Models\CarModel;
use App\Models\FuelType;
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

        CarManufacturer::factory()->create([
            'name' => 'Mercedes',
        ]);

        CarManufacturer::factory()->create([
            'name' => 'Toyota',
        ]);

        CarManufacturer::factory()->create([
            'name' => 'Volkswagen',
        ]);

        CarManufacturer::factory()->create([
            'name' => 'Audi',
        ]);

        CarManufacturer::factory()->create([
            'name' => 'BMW',
        ]);

        CarModel::factory()->create([
            'name' => 'E 220',
            'car_manufacturer_id' => 1
        ]);

        CarModel::factory()->create([
            'name' => 'Corolla',
            'car_manufacturer_id' => 2
        ]);

        CarModel::factory()->create([
            'name' => 'Passat CC',
            'car_manufacturer_id' => 3
        ]);

        CarModel::factory()->create([
            'name' => 'A5',
            'car_manufacturer_id' => 4
        ]);

        CarModel::factory()->create([
            'name' => '325',
            'car_manufacturer_id' => 5
        ]);

        FuelType::factory()->create([
            'name' => 'Benzyna',
        ]);

        FuelType::factory()->create([
            'name' => 'Olej napędowy(diesel)',
        ]);

        FuelType::factory()->create([
            'name' => 'Beznyna + LPG',
        ]);

        FuelType::factory()->create([
            'name' => 'Elektryk',
        ]);

    }
}
