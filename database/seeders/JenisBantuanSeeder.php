<?php

namespace Database\Seeders;

use App\Models\JenisBantuan;
use Illuminate\Database\Seeder;

class JenisBantuanSeeder extends Seeder
{
    public function run(): void
    {
        JenisBantuan::create([
            'kode' => 'PKH',
            'nama_bantuan' => 'Program Keluarga Harapan',
            'deskripsi' => 'Bantuan sosial bersyarat kepada keluarga miskin yang ditetapkan sebagai keluarga penerima manfaat PKH.',
            'status' => true,
        ]);

        JenisBantuan::create([
            'kode' => 'BPNT',
            'nama_bantuan' => 'Bantuan Pangan Non Tunai',
            'deskripsi' => 'Bantuan pangan non tunai dalam rangka menyalurkan bantuan sosial pangan.',
            'status' => true,
        ]);

        JenisBantuan::create([
            'kode' => 'BLT',
            'nama_bantuan' => 'Bantuan Langsung Tunai',
            'deskripsi' => 'Bantuan langsung tunai untuk mengurangi beban pengeluaran KPM.',
            'status' => true,
        ]);
    }
}
