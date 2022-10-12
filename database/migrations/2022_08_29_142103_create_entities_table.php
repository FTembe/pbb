<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name',40);
            $table->string('access_token')->nullable();
            $table->text('description')->nullable();
            $table->string('address',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('email',50)->nullable()->nullable();
            $table->string('aliase',50)->unique()->nullable();
            $table->boolean('status')->nullable();
            $table->string('expire_date',22)->nullable();
            $table->tinyInteger('number_users')->default(3);
            $table->unsignedBigInteger('license_id')->nullable();
            $table->foreign('license_id')->references('id')->on('licenses')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('entities');
    }
}
