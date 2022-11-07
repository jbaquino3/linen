<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockRoom extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = ["storages"];
    protected $hidden = [ 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted() {
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

    public function storages() {
        return $this->hasMany(Storage::class, 'stock_room_id');
    }
}