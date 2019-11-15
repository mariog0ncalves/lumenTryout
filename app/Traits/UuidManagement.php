<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait UuidManagement 
{
    //public $incrementing = false;

    public static function bootUuidManagement()
    {
        static::creating(function ($model) {
            if (!isset($model->attributes[$model->getKeyName()])) {
                $model->incrementing = false;
                $model->attributes[$model->getKeyName()] = Uuid::generate(4, random_bytes(16).rand(1, 1000).microtime(true), Uuid::NS_DNS);
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
