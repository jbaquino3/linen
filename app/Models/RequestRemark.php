<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestRemark extends Model {
    use HasUlids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'remarks',
        'remarks_by',
        'request_id'
    ];

    protected $hidden = [
        'remarks_by',
        'request_id'
    ];
    
    protected function remarks_by_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['remarks_by'] ? User::find($attributes['remarks_by'])->name : null
        );
    }
}