<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model {
    use SoftDeletes;

    protected $primaryKey = 'stock_number';
    public $incrementing = false;

    protected $fillable = [
        'stock_number',
        'description',
        'unit',
        'unit_cost',
        'quantity',
        'type',
        'archived_at',
        'archived_by',
        'storage_id',
        'received_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [ 'archived_by', 'storage_id', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];

    protected $casts = [
        "stock_number" => "integer",
        "unit_cost" => "float",
        "quantity" => "float"
    ];

    protected $appends = [ 'archived_by_name', 'storage_name' ];

    protected static function booted() {
        static::creating(function ($model) {
            $model->created_by = "2010743-create";

            return $model;
        });

        static::updating(function ($model) {
            $model->updated_by = "2010743-update";

            return $model;
        });
    }

    public function getArchivedByNameAttribute($value) {
        return $this->attributes['archived_by'] ? User::find($this->attributes['archived_by'])->name : null;
    }

    public function getStorageNameAttribute($value) {
        return $this->attributes['storage_id'] ? Storage::find($this->attributes['storage_id'])->name : null;
    }
}