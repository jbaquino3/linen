<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $primaryKey = 'employeeid';

    public function getTable() {
        return (config("app.debug") ? "dev" : "dbo") . "." .  Str::snake(Str::pluralStudly(class_basename($this)));
    }

    protected $fillable = [
        'employeeid',
        'name',
        'email',
        'password',
        'location_id',
        'role'
    ];

    protected $hidden = [ 'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at' ];
    protected $casts = [ 'email_verified_at' => 'datetime' ];
    protected $appends = [ 'location_name', 'location_type' ];

    public function getIncrementing() {
        return false;
    }
   
    public function getKeyType() {
        return 'string';
    }

    public function getLocationNameAttribute() {
        return $this->attributes['location_id'] ? Location::find($this->attributes['location_id'])->name : null;
    }

    public function getLocationTypeAttribute() {
        return $this->attributes['location_id'] ? Location::find($this->attributes['location_id'])->type : null;
    }
}
