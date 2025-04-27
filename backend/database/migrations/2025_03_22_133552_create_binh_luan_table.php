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
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_KH')->constrained('khach_hang')->onDelete('cascade');
            $table->foreignId('ID_SP')->constrained('san_pham')->onDelete('cascade');
            $table->text('Noi_dung');
            $table->timestamp('Ngay_binh_luan')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('binh_luan');
    }
};