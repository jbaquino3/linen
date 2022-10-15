<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use HasUlids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'location_id', 'type', 'is_final', 'transaction_date'
    ];

    protected $casts = [
        'is_final' => 'boolean',
    ];

    protected $hidden = ['location_id'];
    protected $with = ['items'];
    
    public function items() {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    protected function location_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['location_id'] ? Location::find($attributes['location_id'])->name : null
        );
    }
}