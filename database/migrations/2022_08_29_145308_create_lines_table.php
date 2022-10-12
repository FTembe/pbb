<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slogan')->nullable();
            $table->boolean('featured')->default(0);
            $table->string('aliase')->nullable();
            $table->string('banner')->nullable();
            $table->string('picture')->nullable();
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->string('category_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('status')->default('1');
            $table->foreign('parent_id')->references('id')->on('lines')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('lines');
    }
}
