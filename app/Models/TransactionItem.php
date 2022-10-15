<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model {
    use HasUlids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'transaction_id', 'product_bulk_id', 'stock_numbers', 'issuance_additional_cost'
    ];
    
    protected function stock_numbers(): Attribute {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value)
        );
    }
}