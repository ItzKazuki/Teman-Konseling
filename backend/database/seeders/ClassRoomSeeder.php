<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = ['RPL', 'DKV', 'ANM'];
        $tingkat = ['X', 'XI', 'XII'];
        $kelas = [1, 2];
        $data_kelas = [];

        foreach ($tingkat as $t) {
            foreach ($jurusan as $j) {
                foreach ($kelas as $k) {
                    $nama_kelas = "{$t} {$j} {$k}";
                    $data_kelas[] = [
                        // --- TAMBAHKAN KOLOM ID DENGAN NILAI UUID ---
                        'id' => (string) Str::uuid(), 
                        
                        'name' => $nama_kelas,
                        'description' => "Kelas {$t} Jurusan {$j} Rombel {$k}",
                        'homeroom_teacher' => null, 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        ClassRoom::insert($data_kelas);
    }
}
