<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockRoom extends Model {
    use HasUlids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    protected $with = ["storages"];

    public function storages() {
        return $this->hasMany(Storage::class, 'stock_room_id');
    }
}