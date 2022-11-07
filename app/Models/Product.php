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
        static::addGlobalScope('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('name', 'asc');
        });
        
        static::creating(function ($model) {
            if(!$model->created_by) {
                $model->created_by = \Auth::check() ? \Auth::id() : null;
            }

            return $model;
        });

        static::updating(function ($model) {
            if(!$model->updated_by) {
                $model->updated_by = \Auth::check() ? \Auth::id() : null;
            }

            return $model;
        });
    }

    protected $hidden = [ 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ];
    protected $appends = ['stock_numbers'];
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

    public function getQuantityAvailableAttribute() {
        return floatval($this->attributes['quantity'] - $this->getQuantityIssuedAttribute());
    }
}