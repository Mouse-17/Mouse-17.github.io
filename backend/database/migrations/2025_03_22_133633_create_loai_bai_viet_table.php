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
        Schema::create('loai_bai_viet', function (Blueprint $table) {
            $table->id();
            $table->string('Ten_loai', 255);
            $table->text('Mo_ta')->nullable();
            $table->bigInteger('Trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loai_bai_viet');
    }
};