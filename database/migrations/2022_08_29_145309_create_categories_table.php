<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slogan')->nullable();
            $table->boolean('featured')->default(0);
            $table->string('aliase')->unique()->nullable();
            $table->string('banner')->nullable();
            $table->string('picture')->nullable();
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->enum('status',['active', 'inactive'])->default('active');
            
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
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
        Schema::dropIfExists('categories');
    }
}
