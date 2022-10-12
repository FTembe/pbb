<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slogan')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('aliase')->unique()->nullable();
            $table->string('banner')->nullable();
            $table->string('picture')->nullable();
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->boolean('status')->default(true);
           
            $table->unsignedBigInteger('parent_id')->nullable();
      
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
