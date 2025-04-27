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
            // Thêm các cột mới
            if (!Schema::hasColumn('don_hang', 'phuong_thuc_tt')) {
                $table->string('phuong_thuc_tt')->default('cod')->after('Trang_thai');
            }
            
            if (!Schema::hasColumn('don_hang', 'trang_thai_tt')) {
                $table->string('trang_thai_tt')->default('pending')->after('phuong_thuc_tt');
            }
            
            if (!Schema::hasColumn('don_hang', 'dia_chi_giao_hang')) {
                $table->string('dia_chi_giao_hang')->nullable()->after('trang_thai_tt');
            }
            
            if (!Schema::hasColumn('don_hang', 'ma_don_hang')) {
                $table->string('ma_don_hang')->nullable()->after('dia_chi_giao_hang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('don_hang', function (Blueprint $table) {
            // Xóa các cột nếu tồn tại
            if (Schema::hasColumn('don_hang', 'phuong_thuc_tt')) {
                $table->dropColumn('phuong_thuc_tt');
            }
            
            if (Schema::hasColumn('don_hang', 'trang_thai_tt')) {
                $table->dropColumn('trang_thai_tt');
            }
            
            if (Schema::hasColumn('don_hang', 'dia_chi_giao_hang')) {
                $table->dropColumn('dia_chi_giao_hang');
            }
            
            if (Schema::hasColumn('don_hang', 'ma_don_hang')) {
                $table->dropColumn('ma_don_hang');
            }
        });
    }
}; 