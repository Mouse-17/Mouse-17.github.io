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
        Schema::create('khuyen_mai', function (Blueprint $table) {
            $table->id();
            $table->string('Ma_KM', 50)->unique();
            $table->string('Ten_khuyen_mai');
            $table->date('Ngay_bat_dau');
            $table->date('Ngay_ket_thuc');
            $table->enum('Loai_khuyen_mai', ['percentage', 'fixed']);
            $table->string('Dieu_kien_ap_dung')->nullable();
            $table->integer('So_luong')->unsigned();
            $table->bigInteger('Trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('khuyen_mai');
    }
};