<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 
        Schema::create('tokens', function (Blueprint $table) {
            $table->string('uid')->primary();   
            $table->string('accessToken',1024)->nullable(false);
            $table->string('refreshToken',1024)->nullable(false);
            $table->datetime("accessTokenExpiresAt")->nullable(false);
            $table->datetime("refreshTokenExpiresAt")->nullable(false);
            $table->timestamps();
            $table->foreign('uid')->references('uid')->on('users')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
};
