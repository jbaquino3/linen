<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as PAT;
use Illuminate\Support\Str;

class PersonalAccessToken extends PAT
{
    public function getTable() {
        return (config("app.debug") ? "dev" : "dbo") . "." .  Str::snake(Str::pluralStudly(class_basename($this)));
    }
}
