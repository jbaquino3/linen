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
            $model->created_by = "2010743-create";

            return $model;
        });

        static::updating(function ($model) {
            $model->updated_by = "2010743-update";

            return $model;
        });
    }

    protected $appends = [ 'transaction_count' ];

    public function getTransactionCountAttribute() {
        return Transaction::where('location_id', $this->attributes['id'])->count();
    }
}