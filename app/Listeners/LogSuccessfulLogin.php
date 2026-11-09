<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        ActivityLog::record('login', 'Pengguna masuk ke sistem.', $event->user->getAuthIdentifier());
    }
}