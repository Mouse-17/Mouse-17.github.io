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
        Schema::create('san', function (Blueprint $table) {
            $table->id();
            $table->string('Ten_san');
            $table->bigInteger('Trang_thai', )->default(1);
            $table->text('Mo_ta')->nullable();
            $table->text('Dia_chi')->nullable();
            $table->string('Hinh_anh');
            $table->integer('hot')->default(0); 
            $table->integer('view')->default(0); 
            $table->integer('bestseller')->default(0);
            $table->integer('So_luong')->unsigned()->default(1); // Thêm cột số lượng sân, mặc định là 1
            $table->time('Thoi_gian_hoat_dong')->nullable();
            $table->foreignId('ID_Loai')->constrained('loai_san')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('san');
    }
};