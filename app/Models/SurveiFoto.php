<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveiFoto extends Model
{
    protected $table = 'survei_foto';

    const UPDATED_AT = null;

    protected $fillable = [
        'survei_id',
        'kategori',
        'file',
    ];

    public function survei(): BelongsTo
    {
        return $this->belongsTo(Survei::class, 'survei_id');
    }
}
