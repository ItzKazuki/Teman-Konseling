<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Data BK (Bimbingan Konseling) ---
        User::create([
            'name' => 'Ibu Lia Permata',
            'nip' => '198001012005011001',
            'email' => 'lia.bk@sekolah.sch.id',
            'phone_number' => '081112244455',
            'password' => Hash::make('password123'),
            'role' => 'bk',
            'jabatan' => 'Koordinator BK',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pak Bima Sakti',
            'nip' => '198505152010022002',
            'email' => 'bima.bk@sekolah.sch.id',
            'phone_number' => '0811353535',
            'password' => Hash::make('password123'),
            'role' => 'bk',
            'jabatan' => 'Staf BK',
            'email_verified_at' => now(),
        ]);

        // --- Data Guru ---
        User::create([
            'name' => 'Bu Tina Agustina',
            'nip' => '197510202000031003',
            'email' => 'tina.agustina@sekolah.sch.id',
            'phone_number' => '085577828424',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'jabatan' => 'Wali Kelas X RPL 1',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pak Rahmat Hidayat',
            'nip' => '199003052015042004',
            'email' => 'rahmat.hidayat@sekolah.sch.id',
            'phone_number' => '0899329762533',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'jabatan' => 'Guru Mapel',
            'email_verified_at' => now(),
        ]);
    }
}
