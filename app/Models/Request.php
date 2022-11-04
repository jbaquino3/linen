<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model {
    use HasUlids, SoftDeletes;

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('requested_at', 'desc');
        });
    }

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'transaction_id',
        'requested_by',
        'processed_by',
        'prepared_by',
        'issued_by',
        'cancelled_by',
        'requested_at',
        'processed_at',
        'prepared_at',
        'issued_at',
        'cancelled_at',
        'deleted_by'
    ];

    protected $hidden = [
        'transaction_id',
        'processed_by',
        'prepared_by',
        'issued_by',
        'cancelled_by',
        'deleted_by',
        'deleted_at', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $with = ['transaction', 'remarks'];
    protected $casts = [
        "quantity" => "float",
        "requested_at" => "datetime:Y-m-d g:i A",
        "processed_at" => "datetime:Y-m-d g:i A",
        "prepared_at" => "datetime:Y-m-d g:i A",
        "issued_at" => "datetime:Y-m-d g:i A",
        "cancelled_at" => "datetime:Y-m-d g:i A",
    ];

    protected $appends = [ 'requested_by_name', 'processed_by_name', 'prepared_by_name', 'issued_by_name', 'cancelled_by_name' ];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function remarks() {
        return $this->hasMany(RequestRemark::class, 'request_id');
    }

    public function getRequestedByNameAttribute($value) {
        return $this->attributes['requested_by'] ? User::find($this->attributes['requested_by'])->name : null;
    }

    public function getProcessedByNameAttribute($value) {
        return $this->attributes['processed_by'] ? User::find($this->attributes['processed_by'])->name : null;
    }

    public function getPreparedByNameAttribute($value) {
        return $this->attributes['prepared_by'] ? User::find($this->attributes['prepared_by'])->name : null;
    }

    public function getIssuedByNameAttribute($value) {
        return $this->attributes['issued_by'] ? User::find($this->attributes['issued_by'])->name : null;
    }

    public function getCancelledByNameAttribute($value) {
        return $this->attributes['cancelled_by'] ? User::find($this->attributes['cancelled_by'])->name : null;
    }
}