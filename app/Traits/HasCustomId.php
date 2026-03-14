<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasCustomId
{
    protected static function bootHasCustomId()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateCustomId();
            }
        });
    }

    /**
     * Generate sequential custom ID safely
     */
    public function generateCustomId(): string
    {
        return DB::transaction(function () {
            $prefix = $this->customIdPrefix;
            $length = $this->customIdLength ?? 3; // default 001

            $last = static::lockForUpdate()
                ->where($this->getKeyName(), 'like', $prefix.'%')
                ->orderBy($this->getKeyName(), 'desc')
                ->first();

            $nextNumber = $last
                ? ((int) substr($last->{$this->getKeyName()}, strlen($prefix))) + 1
                : 1;

            return $prefix . str_pad($nextNumber, $length, '0', STR_PAD_LEFT);
        });
    }
}
