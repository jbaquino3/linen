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
        Schema::create($this->schema . "." . "user_activities", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string("table_name");
            $table->json("row_id");
            $table->enum('type', ['CREATE', 'UPDATE', 'DELETE']);
            $table->string('user_id');
            $table->longText("query");
            $table->datetime('timestamp');

            $table->index('table_name', 'row_id');
            $table->index('user_id');
        });
        
        Schema::create($this->schema . "." . "locations", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string("name");
            $table->enum('type', ['WARD', 'OFFICE']);
            $table->softDeletes();
        });
        
        Schema::create($this->schema . "." . "stock_rooms", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string("name");
            $table->softDeletes();
        });

        Schema::create($this->schema . "." . "storages", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('stock_room_id');
            $table->string("name");
            $table->softDeletes();
            
            $table->index('stock_room_id');
        });

        Schema::create($this->schema. "." . "materials", function (Blueprint $table) {
            $table->integer('stock_number')->primary();
            $table->string("description");
            $table->enum('unit', ['PIECE', 'SPOOL', 'YARD', 'ROLL', 'SACK/BAG']);
            $table->float('unit_cost', 8, 2)->default(0);
            $table->float('quantity', 8, 2)->default(0);
            $table->enum('type', ['RAW', 'READY-MADE']);
            $table->datetime("archived_at")->nullable();
            $table->string("archived_by")->nullable();
            $table->ulid('storage_id')->nullable();
            $table->datetime("received_at");
            $table->softDeletes();
            
            $table->index('archived_by');
            $table->index('storage_id');
        });
        
        Schema::create($this->schema . "." . "products", function (Blueprint $table) {
            $table->string('bulk_id')->primary();
            $table->integer('material_stock_number')->nullable();
            $table->integer('material_quantity')->default(0);
            $table->ulid('storage_id')->nullable();
            $table->json('stock_numbers')->default("[]");
            $table->string('name');
            $table->enum('unit', ['PIECE', 'SPOOL', 'YARD', 'ROLL', 'SACK/BAG']);
            $table->float('unit_cost', 8, 2)->default(0);
            $table->float('quantity', 8, 2)->default(0);
            $table->float('issued_quantity', 8, 2)->default(0);
            $table->float('condemned_quantity', 8, 2)->default(0);
            $table->float('returned_quantity', 8, 2)->default(0);
            $table->float('lost_quantity', 8, 2)->default(0);
            $table->softDeletes();
            
            $table->index('material_stock_number');
            $table->index('storage_id');
        });
        
        Schema::create($this->schema . "." . "transactions", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('location_id');
            $table->enum('type', ['ISSUANCE', 'CONDEMN', 'RETURN', 'LOST']);
            $table->boolean('is_final')->default(false);
            $table->softDeletes();
            
            $table->index('location_id');
        });

        Schema::create($this->schema . "." . "transaction_items", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('transaction_id');
            $table->string('product_bulk_id');
            $table->json('stock_numbers')->default("[]");
            $table->float('issuance_additional_cost', 8, 2)->default(0);
            $table->softDeletes();
            
            $table->index('transaction_id');
            $table->index('product_bulk_id');
        });

        Schema::create($this->schema . "." . "requests", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->float('quantity', 8, 2)->default(0);
            $table->enum('unit', ['PIECE', 'SPOOL', 'YARD', 'ROLL', 'SACK/BAG']);
            $table->ulid('transaction_id')->nullable();
            $table->string('processed_by')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('issued_by')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->datetime('processed_at')->nullable();
            $table->datetime('prepared_at')->nullable();
            $table->datetime('issued_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->softDeletes();
            
            $table->index('transaction_id');
            $table->index('processed_by');
            $table->index('prepared_by');
            $table->index('issued_by');
            $table->index('cancelled_by');
        });

        Schema::create($this->schema . "." . "request_remarks", function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('remarks');
            $table->string('remarks_by');
            $table->ulid('request_id');
            $table->softDeletes();
            
            $table->index('remarks_by');
            $table->index('request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->schema . "." . 'request_remarks');
        Schema::dropIfExists($this->schema . "." . 'requests');
        Schema::dropIfExists($this->schema . "." . 'transaction_items');
        Schema::dropIfExists($this->schema . "." . 'transactions');
        Schema::dropIfExists($this->schema . "." . 'products');
        Schema::dropIfExists($this->schema . "." . 'materials');
        Schema::dropIfExists($this->schema . "." . 'storages');
        Schema::dropIfExists($this->schema . "." . 'stock_rooms');
        Schema::dropIfExists($this->schema . "." . 'locations');
        Schema::dropIfExists($this->schema . "." . 'user_activities');
    }
};
