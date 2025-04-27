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
        Schema::create('san_pham_mau_size', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_Mau')->constrained('mau')->onDelete('cascade');
            $table->foreignId('ID_Kichthuoc')->constrained('size')->onDelete('cascade');
            $table->foreignId('ID_SP')->constrained('san_pham')->onDelete('cascade');
            $table->integer('So_luong')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('san_pham_mau_size');
    }
};