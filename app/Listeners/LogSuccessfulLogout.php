<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if ($event->user) {
            ActivityLog::record('logout', 'Pengguna keluar dari sistem.', $event->user->getAuthIdentifier());
        }
    }
}