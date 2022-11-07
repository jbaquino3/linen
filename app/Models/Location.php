<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model {
    use HasUlids,SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [ 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('name', 'asc');
        });

        static::creating(function ($model) {
            if(!$model->created_by) {
                $model->created_by = \Auth::check() ? \Auth::id() : null;
            }

            return $model;
        });

        static::updating(function ($model) {
            if(!$model->updated_by) {
                $model->updated_by = \Auth::check() ? \Auth::id() : null;
            }

            return $model;
        });
    }
}