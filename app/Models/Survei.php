<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survei extends Model
{
    protected $table = 'survei';

    protected $fillable = [
        'pengajuan_id',
        'status_rumah',
        'kepemilikan_rumah',
        'jenis_lantai',
        'jenis_dinding',
        'jenis_atap',
        'jumlah_kamar',
        'jumlah_penghuni',
        'pekerjaan',
        'penghasilan',
        'jumlah_tanggungan',
        'punya_motor',
        'punya_mobil',
        'punya_sawah',
        'punya_ternak',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'penghasilan' => 'decimal:2',
            'jumlah_kamar' => 'integer',
            'jumlah_penghuni' => 'integer',
            'jumlah_tanggungan' => 'integer',
            'punya_motor' => 'boolean',
            'punya_mobil' => 'boolean',
            'punya_sawah' => 'boolean',
            'punya_ternak' => 'boolean',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function foto(): HasMany
    {
        return $this->hasMany(SurveiFoto::class, 'survei_id');
    }
}
