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
    protected $hidden = [ 'created_at', 'updated_at', 'deleted_at' ];  
    protected $with = ['items'];
    protected $appends = [ 'location_name', 'created_by_name' ];

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('transaction_date', 'desc');
        });

        static::creating(function ($model) {
            if(!$model->created_by) {
                $model->created_by = \Auth::check() ? \Auth::id() : null;
            }

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
        return $this->attributes['created_by'] ? User::find($this->attributes['created_by'])->name : null;
    }
}