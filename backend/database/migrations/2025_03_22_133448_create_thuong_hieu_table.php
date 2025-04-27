<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('thuong_hieu', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->primary('id'); 
            $table->string('Ten_thuong_hieu');
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('thuong_hieu');
    }
};