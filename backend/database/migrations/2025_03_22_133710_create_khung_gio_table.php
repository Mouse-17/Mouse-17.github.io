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
        Schema::create('khung_gio', function (Blueprint $table) {
            $table->id();
            $table->time('Gio_bat_dau');
            $table->time('Gio_ket_thuc');
            $table->decimal('Gia_thue', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('khung_gio');
    }
};