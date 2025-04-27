<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('don_hang', function (Blueprint $table) {
            // Thêm id sản phẩm (có thể null vì không phải đơn hàng nào cũng chỉ có 1 sản phẩm)
            $table->unsignedBigInteger('id_san_pham')->nullable()->after('ID_KH');
            
            // Thêm tên sản phẩm
            $table->string('ten_san_pham')->nullable()->after('id_san_pham');
            
            // Thêm index cho id_san_pham
            $table->index('id_san_pham', 'idx_don_hang_id_san_pham');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('don_hang', function (Blueprint $table) {
            $table->dropIndex('idx_don_hang_id_san_pham');
            $table->dropColumn(['id_san_pham', 'ten_san_pham']);
        });
    }
}; 