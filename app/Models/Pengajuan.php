<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengajuan extends Model
{
    use SoftDeletes;

    protected $table = 'pengajuan';

    protected $fillable = [
        'kode_pengajuan',
        'penerima_id',
        'petugas_id',
        'tanggal_pengajuan',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
        ];
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Penerima::class, 'penerima_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function jenisBantuan(): BelongsToMany
    {
        return $this->belongsToMany(JenisBantuan::class, 'pengajuan_bantuan', 'pengajuan_id', 'jenis_bantuan_id');
    }

    public function survei(): HasOne
    {
        return $this->hasOne(Survei::class, 'pengajuan_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'pengajuan_id');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class, 'pengajuan_id');
    }

    public function penyaluran(): HasMany
    {
        return $this->hasMany(Penyaluran::class, 'pengajuan_id');
    }
}
