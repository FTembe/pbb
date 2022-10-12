<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitySuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_suppliers', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('supllier_id');
            $table->unsignedBigInteger('entity_id');
            $table->foreign('supllier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_suppliers');
    }
}
