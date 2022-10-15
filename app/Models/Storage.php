<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model {
    use HasUlids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'stock_room_id', 'name'
    ];

    protected $hidden = ['stock_room_id'];
    
    public function materials() {
        return $this->hasMany(Material::class, 'storage_id');
    }
}