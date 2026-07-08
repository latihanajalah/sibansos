<?php

namespace App\Helpers;

class StatusHelper
{
    /**
     * Map status value → [label, Bootstrap color, Bootstrap Icon class]
     */
    public static function label(string $status): array
    {
        return match ($status) {
            'menunggu_survei'      => ['Menunggu Survei',       'warning',   'bi-hourglass-split'],
            'menunggu_verifikasi'  => ['Menunggu Verifikasi',   'info',      'bi-shield-exclamation'],
            'revisi_survei'        => ['Revisi Survei',         'warning',   'bi-arrow-clockwise'],
            'menunggu_persetujuan' => ['Menunggu Persetujuan',  'primary',   'bi-person-check'],
            'ditolak_admin'        => ['Ditolak Admin',         'danger',    'bi-x-circle'],
            'ditolak_pimpinan'     => ['Ditolak Pimpinan',      'danger',    'bi-x-circle-fill'],
            'siap_disalurkan'      => ['Siap Disalurkan',       'success',   'bi-truck'],
            'selesai'              => ['Selesai',               'success',   'bi-check-circle-fill'],
            default                => [ucwords(str_replace('_', ' ', $status)), 'secondary', 'bi-circle'],
        };
    }
}
