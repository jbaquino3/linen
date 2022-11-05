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
        'content'
    ];

    protected $appends = ["content"];

    public function getContentAttribute() {
        return json_decode($this->attributes["content"], true);
    }

    public function setContentAttribute($value) {
        $this->attributes["content"] = json_encode($value);
    }
}