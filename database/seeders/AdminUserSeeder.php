<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profiles\AdminsProfile;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'email' => 'admin@molod07.ru',
            'password' => Hash::make('12345678'), // You can change the password
            'role' => 'admin',
        ]);

        // Create corresponding admin profile
        AdminsProfile::create([
            'user_id' => $admin->id,
            'name' => 'Админ',
            'l_name' => 'Админов',
            'f_name' => 'Админович',
            'phone' => '+7(999)999-99-99',
            'permissions' => 'all',

        ]);
    }

}
