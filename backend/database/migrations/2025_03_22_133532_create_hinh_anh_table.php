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
        Schema::create('hinh_anh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_SP')->constrained('san_pham')->onDelete('cascade');
            $table->string('Duong_dan_hinh', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hinh_anh');
    }
};