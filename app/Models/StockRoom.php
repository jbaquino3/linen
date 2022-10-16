<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockRoom extends Model {
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = ["storages"];
    protected $hidden = [ 'created_by', 'updated_by', 'deleted_by']; 

    public function storages() {
        return $this->hasMany(Storage::class, 'stock_room_id');
    }
}