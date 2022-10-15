<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel {
    use HasFactory;

    public function getTable() {
        return (config("app.debug") ? "dev" : "dbo") . "." .  Str::snake(Str::pluralStudly(class_basename($this)));
    }
}
