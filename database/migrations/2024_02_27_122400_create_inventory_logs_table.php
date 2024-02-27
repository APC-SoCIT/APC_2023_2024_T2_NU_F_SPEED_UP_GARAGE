<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryLogsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->integer('quantity');
            $table->timestamp('created_at')->useCurrent();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('inventory_logs');
    }
}
