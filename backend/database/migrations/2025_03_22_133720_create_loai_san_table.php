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
        Schema::create('loai_san', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
             // Tự động tạo khóa chính ID (bigint, auto_increment)
            $table->string('Ten_loai', 255); // Tên loại sân (varchar 255)
            $table->bigInteger('Trang_thai')->default(1); 
            $table->text('Mo_ta')->nullable(); // Mô tả có thể null
            $table->timestamps(); // Tự động tạo created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('loai_san');
    }
};