<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@bansos.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Admin',
            'email' => 'admin@bansos.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Petugas',
            'email' => 'petugas@bansos.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Pimpinan',
            'email' => 'pimpinan@bansos.test',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'status' => 'aktif',
        ]);
    }
}
