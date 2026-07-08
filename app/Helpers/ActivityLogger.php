<?php

namespace App\Helpers;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log a user activity.
     */
    public static function log(string $aktivitas, ?int $userId = null): void
    {
        LogAktivitas::create([
            'user_id'    => $userId ?? Auth::id(),
            'aktivitas'  => $aktivitas,
            'ip_address' => Request::ip(),
            'browser'    => Request::userAgent(),
        ]);
    }
}
