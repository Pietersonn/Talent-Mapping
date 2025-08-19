<?php

namespace App\Traits;

trait HasCustomId
{
    /**
     * Boot the trait
     */
    protected static function bootHasCustomId()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateCustomId();
            }
        });
    }

    /**
     * Generate custom ID - should be implemented in each model
     */
    abstract public function generateCustomId(): string;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return $this->getKeyName();
    }
}
