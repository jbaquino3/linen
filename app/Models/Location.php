<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model {
    use HasUlids,SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type'
    ];
}