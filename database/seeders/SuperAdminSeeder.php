<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@luxuryfashion.com'],
            [
                'name' => 'Luxury Admin',
                'password' => Hash::make('Admin@12345'),
            ]
        );

        $role = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $user->syncRoles([$role]);

        $this->command->info('Super Admin account created!');
    }
}
