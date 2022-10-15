<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model {
    use SoftDeletes;

    protected $primaryKey = 'stock_number';
    public $incrementing = false;
    public $timestamps = false;

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
        'received_at'
    ];

    protected $hidden = [ 'archived_by', 'storage_id' ];

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