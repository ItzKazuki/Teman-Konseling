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
            'name' => 'Lia Permata',
            'nip' => '198001012005011001',
            'email' => 'admin@sekolah.sch.id',
            'phone_number' => '62883888838',
            'password' => Hash::make('password'),
            'role' => 'bk',
            'jabatan' => 'Koordinator BK',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bima Sakti',
            'nip' => '198505152010022002',
            'email' => 'bima.bk@sekolah.sch.id',
            'phone_number' => '62811353535',
            'password' => Hash::make('password'),
            'role' => 'bk',
            'jabatan' => 'Staf BK',
            'email_verified_at' => now(),
        ]);

        // --- Data Guru ---
        User::create([
            'name' => 'Tina Agustina',
            'nip' => '197510202000031003',
            'email' => 'guru@sekolah.sch.id',
            'phone_number' => '6285577828424',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'jabatan' => 'Wali Kelas X RPL 1',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Rahmat Hidayat',
            'nip' => '199003052015042004',
            'email' => 'rahmat.hidayat@sekolah.sch.id',
            'phone_number' => '62899329762533',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'jabatan' => 'Guru Mapel',
            'email_verified_at' => now(),
        ]);

        // staff
        User::create([
            'name' => 'Hariadi',
            'nip' => '19992932938320004',
            'email' => 'staff@sekolah.sch.id',
            'phone_number' => '6283929742983',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'jabatan' => 'Staff TU',
            'email_verified_at' => now(),
        ]);

        // another data
        if ($this->command->confirm('Generate dummy data teacher with Faker?')) {
            User::factory()->count(100)->create();
        }
    }
}
