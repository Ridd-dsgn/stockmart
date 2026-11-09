<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin',          'email' => 'admin@stockmart.test',  'role' => 'admin'],
            ['name' => 'Kepala Toko',    'email' => 'kepala@stockmart.test', 'role' => 'kepala_toko'],
            ['name' => 'Petugas Gudang', 'email' => 'gudang@stockmart.test', 'role' => 'petugas_gudang'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => Hash::make('password')]
            );
        }
    }
}