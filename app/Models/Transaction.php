<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'location_id',
        'type',
        'is_final',
        'transaction_date',
        'created_by'
    ];

    protected $casts = [ 'is_final' => 'boolean' ];
    protected $hidden = [ 'created_by', 'location_id', 'created_at', 'updated_at', 'deleted_at' ];  
    protected $with = ['items'];
    protected $appends = [ 'location_name' ];

    protected static function booted() {
        static::creating(function ($model) {
            $model->created_by = "2010743-create";

            return $model;
        });
    }
    
    public function items() {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function getLocationNameAttribute($value) {
        if($this->attributes['location_id']) {
            $location = Location::find($this->attributes['location_id']);

            return $location->name . " (" . $location->type . ")";
        } else {
            return null;
        }
    }
}