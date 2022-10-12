<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionToAccessLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('access_levels', function (Blueprint $table) {
            $table->text('permission')->nullable();
            $table->string('module')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('access_levels', function (Blueprint $table) {
           $table->dropColumn('permission');
           $table->dropColumn('module');
        });
    }
}
