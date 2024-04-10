<?php

namespace xGrz\PayU\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithStatusChangeObserver
{
    private static function whenStatusChangedTo(Model $model, $status, string $eventClassName): void
    {
        if ($model->status === $status && $model->isDirty('status')) {
            $eventClassName::dispatch($model);
        }
    }

    private static function clearErrorMessage(Model $model, $status): void
    {
        if ($model->error && $model->status !== $status) {
            $model->error = null;
        }
    }

}
