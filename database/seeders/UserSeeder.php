<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'nama_lengkap' => 'Admin MISN FM',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('Admin123'),
            'no_identitas' => '0000000000000000',
            'no_telp' => '081234567890',
            'role' => 'admin_radio',
            'approved_at' => now(),
        ]);

        // Wartawan yang sudah diverifikasi
        User::create([
            'nama_lengkap' => 'Wartawan MISN',
            'email' => 'wartawan@gmail.com',
            'username' => 'wartawan',
            'password' => Hash::make('Wartawan123'),
            'no_identitas' => '1111111111111111',
            'no_telp' => '081234567891',
            'asal_perusahaan' => 'Media Indonesia',
            'role' => 'wartawan',
            'approved_at' => now(),
        ]);

        // Wartawan yang belum diverifikasi
        User::create([
            'nama_lengkap' => 'Wartawan Baru',
            'email' => 'wartawan_baru@gmail.com',
            'username' => 'wartawan_baru',
            'password' => Hash::make('Wartawan123'),
            'no_identitas' => '3333333333333333',
            'no_telp' => '081234567893',
            'asal_perusahaan' => 'Media Baru',
            'role' => 'wartawan',
            'approved_at' => null,
        ]);

        // Pendengar
        User::create([
            'nama_lengkap' => 'Pendengar MISN',
            'email' => 'pendengar@gmail.com',
            'username' => 'pendengar',
            'password' => Hash::make('Pendengar123'),
            'no_identitas' => '2222222222222222',
            'no_telp' => '081234567892',
            'role' => 'pendengar',
            'approved_at' => now(),
        ]);
    }
}