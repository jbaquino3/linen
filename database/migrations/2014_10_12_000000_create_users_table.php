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

    public function up() {
        Schema::create($this->schema . "." . 'users', function (Blueprint $table) {
            $table->string("employeeid")->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->ulid('location_id')->nullable();
            $table->enum('role', ['USER', 'ADMIN', 'SUPER_ADMIN']);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->schema . "." . 'users');
    }
};
