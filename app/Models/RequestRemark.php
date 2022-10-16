<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestRemark extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'remarks',
        'remarks_by',
        'request_id'
    ];

    protected $hidden = [ 'remarks_by', 'request_id', 'created_at', 'updated_at', 'deleted_at' ];
    protected $appends = [ 'remarks_by_name' ];

    public function getRemarksByNameAttribute($value) {
        return $this->attributes['remarks_by'] ? User::find($this->attributes['remarks_by'])->name : null;
    }
}