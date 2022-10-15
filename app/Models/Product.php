<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use SoftDeletes;

    protected $primaryKey = 'bulk_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'bulk_id',
        'material_stock_number',
        'material_quantity',
        'storage_id',
        'stock_numbers',
        'name',
        'unit',
        'unit_cost',
        'quantity',
        'issued_quantity',
        'condemned_quantity',
        'returned_quantity',
        'lost_quantity'
    ];

    protected $hidden = [ 'material_stock_number', 'storage_id' ];

    protected function material_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['material_stock_number'] ? Material::find($attributes['material_stock_number'])->name : null
        );
    }

    protected function storage_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['storage_id'] ? Storage::find($attributes['storage_id'])->name : null
        );
    }

    protected function stock_numbers(): Attribute {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value)
        );
    }
}