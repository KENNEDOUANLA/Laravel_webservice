<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('uid',20)->primary()->default(Str::random(20));
            $table->string('login',50)->unique()->nullable(false);
            $table->string('name',50)->nullable(true);
            $table->string('password',1024)->nullable(false);
            $table->enum('status', ['OPEN', 'CLOSED'])->default("OPEN");
            $table->enum('role', ['ROLE_USER', 'ROLE_ADMIN'])->default("ROLE_USER");
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
};
