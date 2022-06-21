<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Seeding admin user');

        $adminUser = User::where('username', 'admin')->first();

        if (!$adminUser) {
            $user = new User([
                'username' => 'admin',
                'name' => 'Soporte Diego Calle',
                'email' => 'dnetix@gmail.com',
                'role' => '100',
                'active' => true,
            ]);
            $user->setPassword('password');
            $user->save();
        } else {
            $this->command->info('Already seeded admin user');
        }
    }
}
