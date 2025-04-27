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
        Schema::create('mau', function (Blueprint $table) {
            $table->id();
            $table->string('Ten_mau', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mau');
    }
};