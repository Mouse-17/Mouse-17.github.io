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
        Schema::create('don_hang_chi_tiet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_DH')->constrained('don_hang')->onDelete('cascade');
            $table->foreignId('ID_SP')->constrained('san_pham')->onDelete('cascade');
            $table->integer('So_luong')->unsigned()->default(1);
            $table->decimal('Thanh_tien', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('don_hang_chi_tiet');
    }
};