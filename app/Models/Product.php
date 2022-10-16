<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use SoftDeletes;

    protected $primaryKey = 'bulk_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'bulk_id',
        'material_stock_number',
        'material_quantity',
        'storage_id',
        'stock_numbers',
        'name',
        'unit',
        'unit_cost',
        'quantity',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

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

    protected $hidden = [  'material_stock_number', 'storage_id', 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];
    protected $appends = ['stock_numbers', 'material_name', 'storage_name', 'quantity_available'];
    protected $casts = [
        "material_quantity" => "float",
        "unit_cost" => "float",
        "quantity" => "float",
        "material_stock_number" => "integer"
    ];

    public function getMaterialNameAttribute($value) {
        return $this->attributes['material_stock_number'] ? Material::find($this->attributes['material_stock_number'])->description : 'what';
    }

    public function getStorageNameAttribute($value) {
        $storage = Storage::find($this->attributes['storage_id']);
        return $this->attributes['storage_id'] ? $storage->name . ", " . $storage->stock_room_name : null;
    }

    public function getStockNumbersAttribute($value) {
        return json_decode($this->attributes["stock_numbers"], true);
    }

    public function setStockNumbersAttribute($value) {
        $this->attributes["stock_numbers"] = json_encode($value);
    }

    public function getQuantityIssuedAttribute() {
        $trans_items = TransactionItem::where("product_bulk_id", $this->attributes['bulk_id'])->get();
        $sum = 0;
        foreach($trans_items as $item) {
            $sum += $item->quantity;
        }
        return $sum;
    }

    public function getQuantityAvailableAttribute() {
        return floatval($this->attributes['quantity'] - $this->getQuantityIssuedAttribute());
    }
}