<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'employeeid',
        'name',
        'email',
        'password',
        'location_id',
        'role'
    ];

    protected $hidden = [ 'password', 'remember_token', 'location_id' ];
    protected $casts = [ 'email_verified_at' => 'datetime' ];

    public function getIncrementing() {
        return false;
    }
   
    public function getKeyType() {
        return 'string';
    }

    protected function location(): Attribute {
        return Attribute::make(
            get: fn ($value, $attributes) => Location::find($attributes['location_id'])->name
        );
    }
}
