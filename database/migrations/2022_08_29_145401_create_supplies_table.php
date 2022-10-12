<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->float('retail_price')->nullable();
            $table->float('purchase_price')->nullable();
            $table->boolean('availability')->default(1);
            
            $table->smallInteger('quantity')->nullable();
            $table->smallInteger('min_quantity')->default(15);
            $table->string('tax',4)->nullable();
            // $table->smallInteger('remain_quantity')->nullable();
            // $table->tinyInteger('package')->default(false);
            $table->tinyInteger('current')->default(true);
            $table->string('validity',22)->nullable();
            $table->smallInteger('package_quantity')->default(1);
            $table->enum('status',['active','inactive'])->default('active');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplies');
    }
}
