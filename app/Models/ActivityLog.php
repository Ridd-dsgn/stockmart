<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?string $description = null, ?int $userId = null): void
    {
        $userId ??= request()->user()?->id;

        if (! $userId) {
            return; // seeder / console: tidak dicatat
        }

        static::create([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
        ]);
    }
}