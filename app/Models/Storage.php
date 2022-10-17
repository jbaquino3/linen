<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'stock_room_id',
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $hidden = [ 'created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at' ]; 
    
    protected $appends = [ 'stock_room_name', 'storage_name', 'name' ];
    
    public function materials() {
        return $this->hasMany(Material::class, 'storage_id');
    }

    public function getNameAttribute() {
        return ucwords(strtolower($this->attributes['name']));
    }

    public function getStorageNameAttribute() {
        return ucwords(strtolower(StockRoom::find($this->attributes['stock_room_id'])->name . " - " . $this->attributes['name']));
    }

    public function getStockRoomNameAttribute() {
        return $this->attributes['stock_room_id'] ? ucwords(strtolower(StockRoom::find($this->attributes['stock_room_id'])->name)) : null;
    }
}