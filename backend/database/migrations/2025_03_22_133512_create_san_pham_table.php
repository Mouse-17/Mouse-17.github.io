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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id();
            $table->string('Ten_san_pham',500);
            $table->integer('Gia');
            $table->text('Mo_ta')->nullable();
            $table->string('Anh_dai_dien')->nullable();
            $table->integer('hot')->default(0);
            $table->integer('view')->default(0);
            $table->integer('bestseller')->default(0);
            $table->integer('So_luong')->unsigned()->default(0);
            $table->bigInteger('Trang_thai')->default(1);
            $table->foreignId('ID_Thuonghieu')->constrained('thuong_hieu')->onDelete('cascade');
            $table->foreignId('ID_Danhmuc')->nullable()->constrained('danh_muc')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('san_pham');
    }
};