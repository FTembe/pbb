<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('aliase')->unique()->nullable();
            $table->boolean('backend_access')->default(false);
            $table->string('email')->unique();
            $table->enum('status',['inactive','active','block','suspended'])->default('inactive');
            $table->datetime('last_login')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('access_level_id')->onUpdate('cascade')->nullable();
            $table->foreign('access_level_id')->references('id')->on('access_levels')->onDelete('cascade')->onUpdate('cascade');
            // $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
