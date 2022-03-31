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
        Schema::create('ips', function (Blueprint $table) {
            $table->string('Ip_address')->primary(); 
            $table->datetime('next_possible_connexion')->nullable(true);
            $table->datetime('first_request_in_5_min')->nullable(false);
            $table->datetime('last_request_in_5_min')->nullable(false);
            $table->smallInteger('nb_tentive')->nullable(false);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ips');
    }
};
