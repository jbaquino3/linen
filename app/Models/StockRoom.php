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
            $model->created_by = "2010743-create";

            return $model;
        });

        static::updating(function ($model) {
            $model->updated_by = "2010743-update";

            return $model;
        });
    }

    public function storages() {
        return $this->hasMany(Storage::class, 'stock_room_id');
    }
}