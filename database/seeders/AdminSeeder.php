<?php

namespace Database\Seeders;

use App\Models\Admin;
use Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name'      =>'Admin',
            'email'     =>'Admin@gmail.com',
            'password'  =>Hash::make('12345678'),
    ]);
    }
}
