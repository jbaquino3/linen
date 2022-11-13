<?php

namespace App\Models;

class IssuedProduct extends Model {
    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('transaction_date', 'desc');
        });
    }

    protected $casts = [
        "unit_cost" => "float",
        "material_stock_number" => "integer",
        "transaction_date" => "date:M d Y (D)",
        "quantity" => "float",
        "issuance_additional_cost" => "float"
    ];

    protected $appends = ['stock_numbers'];

    public function getStockNumbersAttribute($value) {
        return json_decode($this->attributes["stock_numbers"], true);
    }
}
