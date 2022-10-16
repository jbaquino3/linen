<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'location_id',
        'type',
        'is_final',
        'transaction_date',
        'created_by'
    ];

    protected $casts = [ 'is_final' => 'boolean' ];
    protected $hidden = [ 'created_by', 'location_id', 'created_at', 'updated_at', 'deleted_at' ];  
    protected $with = ['items'];

    protected static function booted() {
        static::creating(function ($model) {
            $model->created_by = "2010743-create";

            return $model;
        });
    }
    
    public function items() {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    protected function location_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['location_id'] ? Location::find($attributes['location_id'])->name : null
        );
    }
}