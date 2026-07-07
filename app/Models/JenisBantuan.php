<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisBantuan extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_bantuan';

    protected $fillable = [
        'kode',
        'nama_bantuan',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function pengajuan(): BelongsToMany
    {
        return $this->belongsToMany(Pengajuan::class, 'pengajuan_bantuan', 'jenis_bantuan_id', 'pengajuan_id');
    }
}
