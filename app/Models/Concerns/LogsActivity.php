<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Support\Str;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(fn ($model) => ActivityLog::record(
            static::activityName($model) . '.created',
            static::activityLabel($model) . ' ditambahkan.'
        ));

        static::updated(function ($model) {
            $changed = collect(array_keys($model->getChanges()))
                ->diff(['updated_at'])
                ->implode(', ');

            if ($changed === '') {
                return;
            }

            ActivityLog::record(
                static::activityName($model) . '.updated',
                static::activityLabel($model) . " diubah ({$changed})."
            );
        });

        static::deleted(fn ($model) => ActivityLog::record(
            static::activityName($model) . '.deleted',
            static::activityLabel($model) . ' dihapus.'
        ));
    }

    protected static function activityName($model): string
    {
        return Str::snake(class_basename($model));
    }

    protected static function activityLabel($model): string
    {
        $name = $model->getAttribute('name') ?? $model->getAttribute('code');

        return class_basename($model) . ' #' . $model->getKey() . ($name ? " ({$name})" : '');
    }
}