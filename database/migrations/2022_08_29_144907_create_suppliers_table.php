<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('aliase')->unique()->nullable();
            $table->string('nuit',75)->unique()->nullable();
            $table->string('phone',15)->nullable();
            $table->string('email',30)->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('address',100)->nullable();
            $table->string('contact_per_name',100)->nullable();
            $table->string('per_contact',30)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('suppliers');
    }
}
