<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserActivity extends Model {
    use HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'table_name',
        'row_id',
        'type',
        'user_id',
        'query',
        'timestamp'
    ];

    protected $hidden = [ 'user_id' ];

    protected function row_id(): Attribute {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value)
        );
    }

    protected function user_name(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['user_id'] ? User::find($attributes['user_id'])->name : null
        );
    }
}