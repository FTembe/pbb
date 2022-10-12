<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierIdToSuppliesTable extends Migration
{
    public function up()
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
        });
    }
}
