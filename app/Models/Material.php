<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model {
    use SoftDeletes;

    protected $primaryKey = 'stock_number';
    public $incrementing = false;

    protected $fillable = [
        'stock_number',
        'description',
        'unit',
        'unit_cost',
        'quantity',
        'type',
        'archived_at',
        'archived_by',
        'storage_id',
        'received_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [ 'archived_by', 'storage_id', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];

    protected $casts = [
        "stock_number" => "integer",
        "unit_cost" => "float",
        "quantity" => "float"
    ];

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
    
    protected function archived_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['archived_by'] ? User::find($attributes['archived_by'])->name : null
        );
    }

    protected function storage_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['storage_id'] ? Storage::find($attributes['storage_id'])->name : null
        );
    }
}