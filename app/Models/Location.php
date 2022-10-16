<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model {
    use HasUlids,SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [ 'deleted_at', 'created_by', 'updated_by', 'deleted_by' ];

    protected static function booted() {
        static::creating(function ($location) {
            $location->created_by = "2010743-create";

            return $location;
        });

        static::updating(function ($location) {
            $location->updated_by = "2010743-update";

            return $location;
        });
    }
}