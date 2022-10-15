<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model {
    use HasUlids, SoftDeletes;
    
    public $timestamps = false;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'transaction_id',
        'requested_by',
        'processed_by',
        'prepared_by',
        'issued_by',
        'cancelled_by',
        'requested_at',
        'processed_at',
        'prepared_at',
        'issued_at',
        'cancelled_at'
    ];

    protected $hidden = [
        'transaction_id',
        'requested_by',
        'processed_by',
        'prepared_by',
        'issued_by',
        'cancelled_by',
        'deleted_at'
    ];

    protected $with = ['transaction', 'remarks'];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function remarks() {
        return $this->hasMany(RequestRemark::class, 'request_id');
    }
    
    protected function requested_by_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['requested_by'] ? User::find($attributes['requested_by'])->name : null
        );
    }
    
    protected function processed_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['processed_by'] ? User::find($attributes['processed_by'])->name : null
        );
    }
    
    protected function prepared_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['prepared_by'] ? User::find($attributes['prepared_by'])->name : null
        );
    }
    
    protected function issued_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['issued_by'] ? User::find($attributes['issued_by'])->name : null
        );
    }
    
    protected function cancelled_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['cancelled_by'] ? User::find($attributes['cancelled_by'])->name : null
        );
    }
}