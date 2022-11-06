<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportItem extends Model {
    use HasUlids, SoftDeletes;

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('created_at', 'asc');
        });
    }

    protected $fillable = [
        'report_id',
        'product_bulk_id',
        'name',
        'unit_cost',
        'beg_balance',
        'issued_quantity',
        'issued_date',
        'total_issued',
        'condemned_quantity',
        'condemned_date',
        'returned_quantity',
        'returned_date',
        'lost_quantity',
        'lost_date',
        'ending_balance'
    ];

    protected $casts = [
        "unit_cost" => "float",
        "beg_balance" => "float",
        "issued_quantity" => "float",
        "total_issued" => "float",
        "condemned_quantity" => "float",
        "returned_quantity" => "float",
        "lost_quantity" => "float",
        "ending_balance" => "float"
    ];
}