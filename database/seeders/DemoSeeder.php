<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Penerima;
use App\Models\JenisBantuan;
use App\Models\Pengajuan;
use App\Models\Survei;
use App\Models\Penyaluran;
use App\Models\RiwayatStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create 5 Petugas
        $petugasList = [];
        for ($i = 1; $i <= 5; $i++) {
            $petugasList[] = User::create([
                'nama' => "Petugas Lapangan $i",
                'email' => "petugas$i@bansos.test",
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'status' => 'aktif',
                'no_hp' => $faker->numerify('08##########'),
            ]);
        }

        // Make sure JenisBantuan exists
        if (JenisBantuan::count() === 0) {
            $this->call(JenisBantuanSeeder::class);
        }
        
        $jenisBantuanIds = JenisBantuan::pluck('id')->toArray();

        // Create 25 Penerima
        $penerimaList = [];
        for ($i = 1; $i <= 25; $i++) {
            $penerimaList[] = Penerima::create([
                'nik' => $faker->unique()->numerify('################'),
                'no_kk' => $faker->numerify('################'),
                'nama' => $faker->name(),
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
                'alamat' => $faker->streetAddress(),
                'rt' => $faker->numerify('00#'),
                'rw' => $faker->numerify('00#'),
                'desa' => 'Desa ' . $faker->citySuffix(),
                'kecamatan' => 'Kecamatan ' . $faker->citySuffix(),
                'kabupaten' => $faker->city(),
                'provinsi' => $faker->state(),
                'no_hp' => $faker->numerify('08##########'),
            ]);
        }

        $statuses = [
            'menunggu_survei', 'menunggu_verifikasi', 'revisi_survei', 
            'menunggu_persetujuan', 'ditolak', 'siap_disalurkan', 'selesai'
        ];

        // Create 25 Pengajuan
        foreach ($penerimaList as $index => $penerima) {
            $petugas = $petugasList[array_rand($petugasList)];
            
            // Random date in the last 6 months
            $tanggal = Carbon::now()->subDays(rand(0, 180));
            $kode = 'PGJ-' . $tanggal->format('Ymd') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            
            // Distribute statuses across the 25 records
            $statusIndex = $index % count($statuses);
            $currentStatus = $statuses[$statusIndex];

            $pengajuan = Pengajuan::create([
                'kode_pengajuan' => $kode,
                'penerima_id' => $penerima->id,
                'petugas_id' => $petugas->id,
                'tanggal_pengajuan' => $tanggal,
                'status' => 'menunggu_survei',
                'keterangan' => 'Pengajuan otomatis dari seeder',
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            // Sync random jenis bantuan
            $pengajuan->jenisBantuan()->sync((array) $jenisBantuanIds[array_rand($jenisBantuanIds)]);

            // Base Riwayat
            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'status' => 'menunggu_survei',
                'user_id' => $petugas->id,
                'catatan' => 'Pengajuan baru',
                'created_at' => $tanggal,
            ]);

            $tanggalStatus = clone $tanggal;

            if ($currentStatus !== 'menunggu_survei') {
                $tanggalStatus->addDays(rand(1, 3));
                
                // create survei
                Survei::create([
                    'pengajuan_id' => $pengajuan->id,
                    'status_rumah' => $faker->randomElement(['Milik Sendiri', 'Sewa', 'Numpang']),
                    'kepemilikan_rumah' => 'Hak Milik',
                    'jenis_lantai' => $faker->randomElement(['Tanah', 'Keramik', 'Semen']),
                    'jenis_dinding' => $faker->randomElement(['Tembok', 'Papan', 'Bambu']),
                    'jenis_atap' => $faker->randomElement(['Genting', 'Seng', 'Asbes']),
                    'jumlah_kamar' => rand(1, 4),
                    'jumlah_penghuni' => rand(2, 7),
                    'pekerjaan' => $faker->jobTitle(),
                    'penghasilan' => rand(500000, 3000000),
                    'jumlah_tanggungan' => rand(1, 5),
                    'punya_motor' => $faker->boolean(60),
                    'punya_mobil' => $faker->boolean(5),
                    'punya_sawah' => $faker->boolean(20),
                    'punya_ternak' => $faker->boolean(40),
                    'catatan' => 'Kondisi sesuai dengan data',
                    'created_at' => $tanggalStatus,
                ]);

                RiwayatStatus::create([
                    'pengajuan_id' => $pengajuan->id,
                    'status' => 'menunggu_verifikasi',
                    'user_id' => $petugas->id,
                    'catatan' => 'Survei selesai',
                    'created_at' => $tanggalStatus,
                ]);

                $pengajuan->update(['status' => 'menunggu_verifikasi', 'updated_at' => $tanggalStatus]);

                if ($currentStatus === 'revisi_survei') {
                    $tanggalStatus->addDays(rand(1, 2));
                    $adminId = User::where('role', 'admin')->first()->id ?? 1;
                    RiwayatStatus::create([
                        'pengajuan_id' => $pengajuan->id,
                        'status' => 'revisi_survei',
                        'user_id' => $adminId,
                        'catatan' => 'Foto kurang jelas',
                        'created_at' => $tanggalStatus,
                    ]);
                    $pengajuan->update(['status' => 'revisi_survei', 'updated_at' => $tanggalStatus]);
                } 
                elseif (in_array($currentStatus, ['menunggu_persetujuan', 'ditolak', 'siap_disalurkan', 'selesai'])) {
                    $tanggalStatus->addDays(rand(1, 2));
                    $adminId = User::where('role', 'admin')->first()->id ?? 1;
                    
                    RiwayatStatus::create([
                        'pengajuan_id' => $pengajuan->id,
                        'status' => 'menunggu_persetujuan',
                        'user_id' => $adminId,
                        'catatan' => 'Data valid',
                        'created_at' => $tanggalStatus,
                    ]);
                    $pengajuan->update(['status' => 'menunggu_persetujuan', 'updated_at' => $tanggalStatus]);

                    if ($currentStatus === 'ditolak') {
                        $tanggalStatus->addDays(rand(1, 2));
                        $pimpinanId = User::where('role', 'pimpinan')->first()->id ?? 1;
                        RiwayatStatus::create([
                            'pengajuan_id' => $pengajuan->id,
                            'status' => 'ditolak',
                            'user_id' => $pimpinanId,
                            'catatan' => 'Kuota penuh',
                            'created_at' => $tanggalStatus,
                        ]);
                        $pengajuan->update(['status' => 'ditolak', 'updated_at' => $tanggalStatus]);
                    }
                    elseif (in_array($currentStatus, ['siap_disalurkan', 'selesai'])) {
                        $tanggalStatus->addDays(rand(1, 2));
                        $pimpinanId = User::where('role', 'pimpinan')->first()->id ?? 1;
                        RiwayatStatus::create([
                            'pengajuan_id' => $pengajuan->id,
                            'status' => 'siap_disalurkan',
                            'user_id' => $pimpinanId,
                            'catatan' => 'Disetujui',
                            'created_at' => $tanggalStatus,
                        ]);
                        $pengajuan->update(['status' => 'siap_disalurkan', 'updated_at' => $tanggalStatus]);

                        if ($currentStatus === 'selesai') {
                            $tanggalStatus->addDays(rand(1, 5));
                            Penyaluran::create([
                                'pengajuan_id' => $pengajuan->id,
                                'petugas_id' => $petugas->id,
                                'tanggal' => $tanggalStatus,
                                'status' => 'berhasil',
                                'catatan' => 'Bantuan telah diterima langsung oleh yang bersangkutan',
                                'created_at' => $tanggalStatus,
                                'updated_at' => $tanggalStatus,
                            ]);
                            RiwayatStatus::create([
                                'pengajuan_id' => $pengajuan->id,
                                'status' => 'selesai',
                                'user_id' => $petugas->id,
                                'catatan' => 'Penyaluran berhasil',
                                'created_at' => $tanggalStatus,
                            ]);
                            $pengajuan->update(['status' => 'selesai', 'updated_at' => $tanggalStatus]);
                        }
                    }
                }
            }
        }
    }
}
