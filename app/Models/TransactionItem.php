<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'product_bulk_id',
        'stock_numbers',
        'issuance_additional_cost'
    ];
    protected $appends = ['stock_numbers'];
    public function getStockNumbersAttribute($value) {
        return json_decode($value, true);
    }

    public function setStockNumbersAttribute($value) {
        $this->attributes["stock_numbers"] = json_encode($value);
    }
}