<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('don_hang', function (Blueprint $table) {
            // Kiểm tra và thêm cột Ma_don_hang nếu chưa tồn tại
            if (!Schema::hasColumn('don_hang', 'Ma_don_hang')) {
                $table->string('Ma_don_hang')->after('ID_KH')->nullable()->comment('Mã đơn hàng dùng để theo dõi');
            }
            
            // Kiểm tra và thêm cột id_san_pham nếu chưa tồn tại
            if (!Schema::hasColumn('don_hang', 'id_san_pham')) {
                $table->unsignedBigInteger('id_san_pham')->nullable()->after('Ma_don_hang');
            }
            
            // Kiểm tra và thêm cột ten_san_pham nếu chưa tồn tại
            if (!Schema::hasColumn('don_hang', 'ten_san_pham')) {
                $table->string('ten_san_pham')->nullable()->after('id_san_pham');
            }
            
            // Kiểm tra và thêm index cho các cột mới
            $indexExists = DB::select("SHOW INDEX FROM don_hang WHERE Key_name = 'idx_don_hang_ma_don_hang'");
            if (count($indexExists) == 0 && Schema::hasColumn('don_hang', 'Ma_don_hang')) {
                $table->index('Ma_don_hang', 'idx_don_hang_ma_don_hang');
            }
            
            $indexExists = DB::select("SHOW INDEX FROM don_hang WHERE Key_name = 'idx_don_hang_id_san_pham'");
            if (count($indexExists) == 0 && Schema::hasColumn('don_hang', 'id_san_pham')) {
                $table->index('id_san_pham', 'idx_don_hang_id_san_pham');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('don_hang', function (Blueprint $table) {
            // Xóa các index nếu tồn tại
            $indexExists = DB::select("SHOW INDEX FROM don_hang WHERE Key_name = 'idx_don_hang_ma_don_hang'");
            if (count($indexExists) > 0) {
                $table->dropIndex('idx_don_hang_ma_don_hang');
            }
            
            $indexExists = DB::select("SHOW INDEX FROM don_hang WHERE Key_name = 'idx_don_hang_id_san_pham'");
            if (count($indexExists) > 0) {
                $table->dropIndex('idx_don_hang_id_san_pham');
            }
            
            // Xóa các cột nếu tồn tại
            $columns = [];
            if (Schema::hasColumn('don_hang', 'Ma_don_hang')) {
                $columns[] = 'Ma_don_hang';
            }
            if (Schema::hasColumn('don_hang', 'id_san_pham')) {
                $columns[] = 'id_san_pham';
            }
            if (Schema::hasColumn('don_hang', 'ten_san_pham')) {
                $columns[] = 'ten_san_pham';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
}; 