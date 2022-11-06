<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Model extends BaseModel {
    use HasFactory;

    public function getTable() {
        return (config("app.debug") ? "dev" : "dbo") . "." .  Str::snake(Str::pluralStudly(class_basename($this)));
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($this->attributes['created_at']))
            ->timezone('Asia/Manila')
            ->format("Y-m-d g:i A")
        ;
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($this->attributes['updated_at']))
            ->timezone('Asia/Manila')
            ->format("Y-m-d g:i A")
        ;
    }
}
