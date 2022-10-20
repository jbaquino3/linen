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
    protected $hidden = [ 'location_id', 'created_at', 'updated_at', 'deleted_at' ];  
    protected $with = ['items'];
    protected $appends = [ 'location_name', 'created_by_name' ];

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('transaction_date', 'desc');
        });

        static::creating(function ($model) {
            $model->created_by = "2010743";

            return $model;
        });
    }
    
    public function items() {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function getLocationNameAttribute() {
        if($this->attributes['location_id']) {
            $location = Location::find($this->attributes['location_id']);

            return $location->name . " (" . $location->type . ")";
        } else {
            return null;
        }
    }

    public function getCreatedByNameAttribute() {
        return User::find($this->attributes['created_by'])->name;
    }
}