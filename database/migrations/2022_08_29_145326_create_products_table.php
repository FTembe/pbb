<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order')->nullable();
            $table->string('name');
            $table->string('aliase');
            $table->string('slogan')->nullable();
            $table->boolean('status')->default(true);
            $table->enum('type', ['produto', 'serviço', 'equipamento'])->default('produto');
            $table->text('images')->nullable();

            $table->boolean('featured')->default(0)->nullable();
            $table->boolean('recent')->default(0)->nullable();

            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->text('tags')->nullable();

            $table->text('retail_price')->nullable();

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('line_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
