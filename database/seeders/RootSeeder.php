<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $root = Role::firstOrCreate(
            ['name' => 'root'],
            [
                'guard_name' => 'web',
                'is_system' => true,
            ]
        );

        $rootUser = User::firstOrCreate(
            ['email' => 'root@gmail.com'],
            [
                'name' => 'Root',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        if (!$rootUser->hasRole('root')) {
            $rootUser->assignRole($root);
        }
    }
}
