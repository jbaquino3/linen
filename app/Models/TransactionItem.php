<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'product_bulk_id',
        'stock_numbers',
        'issuance_additional_cost'
    ];
    protected $appends = ['stock_numbers', 'quantity'];
    protected $hidden = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $casts = [
        "issuance_additional_cost" => "float"
    ];

    public function getStockNumbersAttribute() {
        return json_decode($this->attributes["stock_numbers"], true);
    }

    public function getQuantityAttribute() {
        return sizeof($this->getStockNumbersAttribute());
    }

    public function setStockNumbersAttribute($value) {
        $this->attributes["stock_numbers"] = json_encode($value);
    }
}