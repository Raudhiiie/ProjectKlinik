<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Terapis 1',
            'username' => 'terapis',
            'email' => 'terapis1@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Mawar No.1',
            'tanggal_lahir' => '1995-05-01',
            'tanggal_bergabung' => now()->toDateString(),
            'password' => Hash::make('terapis'),
            'role' => 'terapis',
        ]);

        User::create([
            'nama' => 'Dokter 1',
            'username' => 'dokter',
            'email' => 'dokter1@example.com',
            'no_hp' => '089876543210',
            'alamat' => 'Jl. Melati No.2',
            'tanggal_lahir' => '1990-02-15',
            'tanggal_bergabung' => now()->toDateString(),
            'password' => Hash::make('dokter'),
            'role' => 'dokter',
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
