<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model {
    use HasUlids, SoftDeletes;

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

    protected $fillable = [
        'location_id',
        'generated_by',
        'month',
        'year'
    ];

    protected $with = ["items"];

    public function items() {
        return $this->hasMany(ReportItem::class, 'report_id');
    }
}