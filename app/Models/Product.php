<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


class Product extends Model {
    use SoftDeletes, HasUlids;

    protected $primaryKey = 'bulk_id';

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
        'create_date',
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

    protected $hidden = [ 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];
    protected $appends = ['stock_numbers', 'quantity_issued', 'quantity_condemned', 'quantity_returned', 'quantity_lost'];
    protected $with = [ 'material', 'storage' ];
    protected $casts = [
        "material_quantity" => "float",
        "unit_cost" => "float",
        "quantity" => "float",
        "material_stock_number" => "integer"
    ];

    public function material() {
        return $this->belongsTo(Material::class, 'material_stock_number');
    }

    public function storage() {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function getStockNumbersAttribute($value) {
        return json_decode($this->attributes["stock_numbers"], true);
    }

    public function setStockNumbersAttribute($value) {
        $this->attributes["stock_numbers"] = json_encode($value);
    }

    public function getQuantityIssuedAttribute() {
        $trans_items = TransactionItem::leftJoin((config("app.debug") ? "dev" : "dbo") . ".transactions", "transaction_items.transaction_id", "=", "transactions.id")
            ->where("transaction_items.product_bulk_id", $this->attributes['bulk_id'])
            ->where("transactions.type", "ISSUANCE")
            ->get();

        $sum = 0;
        foreach($trans_items as $item) {
            $sum += $item->quantity;
        }
        return $sum;
    }

    public function getQuantityCondemnedAttribute() {
        $trans_items = TransactionItem::leftJoin((config("app.debug") ? "dev" : "dbo") . ".transactions", "transaction_items.transaction_id", "=", "transactions.id")
            ->where("transaction_items.product_bulk_id", $this->attributes['bulk_id'])
            ->where("transactions.type", "CONDEMN")
            ->get();

        $sum = 0;
        foreach($trans_items as $item) {
            $sum += $item->quantity;
        }
        return $sum;
    }

    public function getQuantityReturnedAttribute() {
        $trans_items = TransactionItem::leftJoin((config("app.debug") ? "dev" : "dbo") . ".transactions", "transaction_items.transaction_id", "=", "transactions.id")
            ->where("transaction_items.product_bulk_id", $this->attributes['bulk_id'])
            ->where("transactions.type", "RETURN")
            ->get();

        $sum = 0;
        foreach($trans_items as $item) {
            $sum += $item->quantity;
        }
        return $sum;
    }

    public function getQuantityLostAttribute() {
        $trans_items = TransactionItem::leftJoin((config("app.debug") ? "dev" : "dbo") . ".transactions", "transaction_items.transaction_id", "=", "transactions.id")
            ->where("transaction_items.product_bulk_id", $this->attributes['bulk_id'])
            ->where("transactions.type", "LOST")
            ->get();

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