<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLevelPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_level_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unsignedBigInteger('access_level_id')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreign('access_level_id')->references('id')->on('access_levels');
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
        Schema::dropIfExists('access_level_permissions');
    }
}
