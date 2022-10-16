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

    protected $appends = [ 'archived_by_name', 'storage_name', 'quantity_available' ];

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

    public function getArchivedByNameAttribute() {
        return $this->attributes['archived_by'] ? User::find($this->attributes['archived_by'])->name : null;
    }

    public function getStorageNameAttribute($value) {
        $storage = Storage::find($this->attributes['storage_id']);
        return $this->attributes['storage_id'] ? $storage->name . ", " . $storage->stock_room_name : null;
    }

    public function getQuantityUsedAttribute() {
        return floatval(Product::where("material_stock_number", $this->attributes['stock_number'])->sum('material_quantity'));
    }

    public function getQuantityAvailableAttribute() {
        return floatval($this->attributes['quantity'] - $this->getQuantityUsedAttribute());
    }
}