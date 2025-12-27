<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = ['RPL', 'DKV', 'ANM'];

        $tingkatMap = [
            'X' => 10,
            'XI' => 11,
            'XII' => 12,
        ];

        $kelas = [1, 2];
        $data_kelas = [];
        foreach ($tingkatMap as $tingkat => $level) {
            foreach ($jurusan as $jurusanItem) {
                foreach ($kelas as $rombel) {
                    $nama_kelas = "{$tingkat} {$jurusanItem} {$rombel}";

                    $data_kelas[] = [
                        'id' => (string) Str::uuid(),
                        'name' => $nama_kelas,
                        'level' => $level,
                        'description' => "Kelas {$tingkat} Jurusan {$jurusanItem} Rombel {$rombel}",
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
