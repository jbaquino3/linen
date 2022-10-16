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

    protected $hidden = [ 'archived_by', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];

    protected $casts = [
        "stock_number" => "integer",
        "unit_cost" => "float",
        "quantity" => "float"
    ];

    protected $appends = [ 'archived_by_name', 'storage_name', 'quantity_used' ];

    protected static function booted() {
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('stock_number', 'desc');
        });

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
        return $this->attributes['storage_id'] ? ucwords(strtolower($storage->stock_room_name . " - " . $storage->name)) : null;
    }

    public function getQuantityUsedAttribute() {
        $value = strval(Product::where("material_stock_number", $this->attributes['stock_number'])->sum('material_quantity'));
        $value = ((int) strpos(strrev($value), ".")) > 2 ? round($value, 2) : $value;

        return floatval($value);
    }

    public function getQuantityAvailableAttribute() {
        $value = strval($this->attributes['quantity'] - $this->getQuantityUsedAttribute());
        $value = ((int) strpos(strrev($value), ".")) > 2 ? round($value, 2) : $value;

        return floatval($value);
    }
}