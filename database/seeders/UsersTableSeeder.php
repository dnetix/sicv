<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make($this->command->secret('Password for admin user')),
            'name' => 'Soporte Diego Calle',
            'email' => 'dnetix@gmail.com',
            'role' => '100',
            'active' => true,
        ]);
    }
}
