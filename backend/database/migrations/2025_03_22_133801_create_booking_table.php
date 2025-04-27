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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kh')->constrained('khach_hang');
            $table->foreignId('id_san')->constrained('san');
            $table->foreignId('id_kg')->constrained('khung_gio');
            $table->date('Ngay_dat');
            $table->bigInteger('Trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking');
    }
};