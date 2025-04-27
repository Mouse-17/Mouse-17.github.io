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
        Schema::create('danh_gia_san', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_KH')->constrained('khach_hang')->onDelete('cascade');
            $table->foreignId('ID_San')->constrained(table: 'san')->onDelete('cascade');
            $table->integer('So_sao')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('danh_gia_san');
    }
};