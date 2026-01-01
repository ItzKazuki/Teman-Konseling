<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class MotivationController extends Controller
{
    public function index()
    {
        $motivations = [
            1 => 'Setiap hari adalah kesempatan baru untuk menjadi lebih baik.',
            2 => 'Kesuksesan dimulai dari langkah kecil yang konsisten.',
            3 => 'Jangan takut gagal, takutlah berhenti mencoba.',
            4 => 'Proses hari ini menentukan hasil esok hari.',
            5 => 'Kerja keras tidak pernah mengkhianati hasil.',
            6 => 'Fokus pada tujuan, bukan pada hambatan.',
            7 => 'Disiplin mengalahkan motivasi yang sementara.',
            8 => 'Belajar dari kesalahan adalah tanda kedewasaan.',
            9 => 'Mimpi besar dimulai dari keberanian kecil.',
            10 => 'Waktu terbaik untuk mulai adalah sekarang.',

            11 => 'Jangan bandingkan prosesmu dengan orang lain.',
            12 => 'Konsistensi kecil lebih baik dari niat besar.',
            13 => 'Kesabaran adalah kekuatan yang sering diremehkan.',
            14 => 'Kegagalan adalah guru terbaik dalam hidup.',
            15 => 'Tetap bergerak meski pelan.',
            16 => 'Usaha tidak akan mengkhianati doa.',
            17 => 'Jangan menunggu sempurna untuk memulai.',
            18 => 'Setiap langkah membawa pengalaman baru.',
            19 => 'Berani mencoba sudah selangkah lebih maju.',
            20 => 'Keraguan adalah musuh terbesar potensi.',

            21 => 'Percaya diri dibangun dari usaha, bukan kata orang.',
            22 => 'Hari berat membentuk pribadi yang kuat.',
            23 => 'Tujuan besar butuh ketekunan jangka panjang.',
            24 => 'Belajar hari ini, menuai esok hari.',
            25 => 'Jangan menyerah hanya karena lelah.',
            26 => 'Keberhasilan adalah hasil dari kebiasaan baik.',
            27 => 'Setiap masalah pasti ada solusi.',
            28 => 'Fokus pada progres, bukan kesempurnaan.',
            29 => 'Kegigihan lebih penting dari bakat.',
            30 => 'Masa depan dibangun dari keputusan hari ini.',

            31 => 'Tidak apa-apa istirahat, asal jangan berhenti.',
            32 => 'Semua orang pernah berada di titik ragu.',
            33 => 'Keberanian muncul setelah mencoba.',
            34 => 'Kesuksesan membutuhkan pengorbanan.',
            35 => 'Belajarlah dari hari kemarin.',
            36 => 'Hargai setiap proses yang kamu jalani.',
            37 => 'Kesalahan bukan akhir segalanya.',
            38 => 'Terus belajar agar tidak tertinggal.',
            39 => 'Mimpi perlu aksi nyata.',
            40 => 'Satu langkah lebih baik daripada diam.',

            41 => 'Tekad kuat mengalahkan rasa takut.',
            42 => 'Kesabaran menghasilkan ketenangan.',
            43 => 'Percaya proses akan membuahkan hasil.',
            44 => 'Jangan remehkan usaha kecilmu.',
            45 => 'Kesuksesan adalah perjalanan panjang.',
            46 => 'Kegagalan hanyalah penundaan keberhasilan.',
            47 => 'Usaha hari ini adalah investasi masa depan.',
            48 => 'Jangan berhenti saat hampir berhasil.',
            49 => 'Semua bisa dipelajari dengan niat.',
            50 => 'Kesungguhan membawa perubahan.',

            51 => 'Tidak ada hasil tanpa perjuangan.',
            52 => 'Keberhasilan butuh waktu.',
            53 => 'Tetap rendah hati saat berhasil.',
            54 => 'Bangkit adalah pilihan.',
            55 => 'Jangan biarkan rasa takut menguasai diri.',
            56 => 'Disiplin adalah bentuk cinta pada diri sendiri.',
            57 => 'Hidup adalah proses belajar tanpa henti.',
            58 => 'Jangan menyerah pada keadaan.',
            59 => 'Kamu lebih kuat dari yang kamu kira.',
            60 => 'Kesulitan membentuk karakter.',

            61 => 'Keberanian tumbuh dari pengalaman.',
            62 => 'Jangan menunda apa yang bisa dimulai.',
            63 => 'Setiap hari adalah pelajaran baru.',
            64 => 'Ketekunan melahirkan keahlian.',
            65 => 'Percaya diri datang dari persiapan.',
            66 => 'Jangan takut terlihat bodoh saat belajar.',
            67 => 'Proses tidak pernah berbohong.',
            68 => 'Tujuan jelas memudahkan langkah.',
            69 => 'Fokus pada hal yang bisa dikendalikan.',
            70 => 'Kerja keras selalu bernilai.',

            71 => 'Kesuksesan tidak datang secara instan.',
            72 => 'Belajar menerima kritik dengan bijak.',
            73 => 'Semangat tumbuh dari niat yang benar.',
            74 => 'Kegagalan bukan identitas.',
            75 => 'Tetap maju meski perlahan.',
            76 => 'Hasil besar berasal dari kebiasaan kecil.',
            77 => 'Kejujuran membawa ketenangan.',
            78 => 'Berhenti mengeluh, mulai bertindak.',
            79 => 'Setiap usaha mendekatkan tujuan.',
            80 => 'Waktu tidak menunggu siapa pun.',

            81 => 'Kesuksesan milik mereka yang sabar.',
            82 => 'Belajar dari siapa pun.',
            83 => 'Jangan takut berbeda.',
            84 => 'Fokus membuatmu lebih produktif.',
            85 => 'Hidup bukan perlombaan.',
            86 => 'Kerja cerdas melengkapi kerja keras.',
            87 => 'Kesungguhan menghasilkan kepercayaan.',
            88 => 'Jangan berhenti berkembang.',
            89 => 'Tujuan hidup memberi arah.',
            90 => 'Usaha kecil yang rutin membawa hasil.',

            91 => 'Semua dimulai dari niat.',
            92 => 'Tetap optimis di situasi sulit.',
            93 => 'Ketekunan adalah kunci.',
            94 => 'Belajar menerima proses.',
            95 => 'Keberhasilan adalah hasil konsistensi.',
            96 => 'Hargai dirimu sendiri.',
            97 => 'Terus maju tanpa menoleh ke belakang.',
            98 => 'Kesuksesan adalah milik mereka yang berani.',
            99 => 'Setiap hari adalah peluang.',
            100 => 'Jangan pernah berhenti mencoba.',
        ];

        return ApiResponse::success(
            Arr::random($motivations)
        );
    }
}
