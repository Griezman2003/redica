<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        //$clienteRole = Role::firstOrCreate(['name' => 'cliente', 'guard_name' => 'web']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);

        $admin->assignRole($adminRole);
    }
}