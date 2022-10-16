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

    protected $hidden = [ 'created_by', 'updated_by', 'deleted_by', 'stock_room_id', 'created_at', 'updated_at', 'deleted_at' ]; 
    
    public function materials() {
        return $this->hasMany(Material::class, 'storage_id');
    }
}