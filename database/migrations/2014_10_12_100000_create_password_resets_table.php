<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $schema;

    public function __construct() {
        $this->schema = config("app.debug") ? "dev" : "dbo";
    }

    public function up()
    {
        Schema::create($this->schema . "." . 'password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->schema . "." . 'password_resets');
    }
};
