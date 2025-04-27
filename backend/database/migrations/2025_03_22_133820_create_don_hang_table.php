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
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id();
            $table->date('Ngay_mua');
            $table->decimal('Tong_tien', 10, 2);
            $table->bigInteger('Trang_thai')->default(1);
            $table->foreignId('ID_KH')->constrained('khach_hang')->onDelete('cascade');
            $table->foreignId('ID_Khuyenmai')->nullable()->constrained('khuyen_mai')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('don_hang');
    }
};