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

        static::creating(function ($model) {
            if(!$model->generated_by) {
                $model->generated_by = \Auth::check() ? \Auth::id() : null;
            }

            return $model;
        });
    }

    protected $fillable = [
        'location_id',
        'location_name',
        'generated_by',
        'month',
        'year'
    ];

    protected $with = ["items"];
    protected $appends = ["generated_by_name"];

    public function items() {
        return $this->hasMany(ReportItem::class, 'report_id');
    }

    public function getGeneratedByNameAttribute($value) {
        return $this->attributes['generated_by'] ? User::find($this->attributes['generated_by'])->name : null;
    }
}