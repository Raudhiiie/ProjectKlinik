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
        User::firstOrCreate(
            ['email' => 'terapis1@example.com'], // cari berdasarkan email
            [
                'nama' => 'Terapis',
                'username' => 'terapis',
                'email' => 'terapis1@example.com',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Mawar No.1',
                'tanggal_lahir' => '1995-05-01',
                'tanggal_bergabung' => now()->toDateString(),
                'password' => Hash::make('terapis'),
                'role' => 'terapis',
            ]
        );
        User::firstOrCreate(
            ['email' => 'pretty@gmail.com'], // cari berdasarkan email
            [
                'nama' => 'dr. Pretty Diandani',
                'username' => 'dokter',
                'email' => 'pretty@gmail.com',
                'no_hp' => '082284591222',
                'alamat' => 'Jl. Melati No.2',
                'tanggal_lahir' => '1990-02-15',
                'tanggal_bergabung' => now()->toDateString(),
                'password' => Hash::make('dokter'),
                'role' => 'dokter',
            ]
        );

        User::create([
            'nama' => 'Nirmala',
            'username' => 'nirmala',
            'email' => 'nirmala@gmail.com',
            'no_hp' => '089654593666',
            'alamat' => 'Jl. Tegal Sari Km 4 Gg, Cindelaras',
            'tanggal_lahir' => '2001-11-16',
            'tanggal_bergabung' => '2025-01-20',
            'password' => Hash::make('nirmala'),
            'role' => 'terapis',
        ]);

        User::create([
            'nama' => 'Kevina',
            'username' => 'kevina',
            'email' => 'kevina@gmail.com',
            'no_hp' => '082154327765',
            'alamat' => 'Jl. Cikpuan ',
            'tanggal_lahir' => '2000-10-10',
            'tanggal_bergabung' => '2024-03-12',
            'password' => Hash::make('kevina'),
            'role' => 'terapis',
        ]);

        User::create([
            'nama' => 'Sri Kesuma Astuti',
            'username' => 'srikesuma',
            'email' => 'srikesuma@gmail.com',
            'no_hp' => '082286552320',
            'alamat' => 'Jl. Sultan Syarif Kasim Gg. rajawali',
            'tanggal_lahir' => '2001-11-16',
            'tanggal_bergabung' => '2025-03-21',
            'password' => Hash::make('srikesuma'),
            'role' => 'terapis',
        ]);

        User::create([
            'nama' => 'Laura Okta Vanjola',
            'username' => 'laura',
            'email' => 'laura@gmail.com',
            'no_hp' => '081371297754',
            'alamat' => 'Jl. Mawar',
            'tanggal_lahir' => '2001-08-11',
            'tanggal_bergabung' => '2025-03-28',
            'password' => Hash::make('laura'),
            'role' => 'terapis',
        ]);
    }
}
