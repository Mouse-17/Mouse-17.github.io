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
        Schema::table('booking', function (Blueprint $table) {
            // Thêm các cột mới cho thông tin khách hàng
            if (!Schema::hasColumn('booking', 'Ten_KH')) {
                $table->string('Ten_KH')->nullable();
            }
            if (!Schema::hasColumn('booking', 'SDT')) {
                $table->string('SDT')->nullable();
            }
            if (!Schema::hasColumn('booking', 'Email')) {
                $table->string('Email')->nullable();
            }
            if (!Schema::hasColumn('booking', 'Ghi_chu')) {
                $table->text('Ghi_chu')->nullable();
            }
            if (!Schema::hasColumn('booking', 'Tong_tien')) {
                $table->decimal('Tong_tien', 10, 2)->default(0);
            }
            
            // Thêm các cột liên quan đến thanh toán
            if (!Schema::hasColumn('booking', 'phuong_thuc_thanh_toan')) {
                $table->tinyInteger('phuong_thuc_thanh_toan')->default(1)->comment('1: Thanh toán tại sân, 2: Chuyển khoản, 3: Thanh toán online');
            }
            if (!Schema::hasColumn('booking', 'trang_thai_thanh_toan')) {
                $table->tinyInteger('trang_thai_thanh_toan')->default(0)->comment('0: Chưa thanh toán, 1: Đã thanh toán một phần, 2: Đã thanh toán');
            }
            if (!Schema::hasColumn('booking', 'ma_giao_dich')) {
                $table->string('ma_giao_dich')->nullable();
            }
            if (!Schema::hasColumn('booking', 'thoi_gian_thanh_toan')) {
                $table->timestamp('thoi_gian_thanh_toan')->nullable();
            }
            if (!Schema::hasColumn('booking', 'ghi_chu_thanh_toan')) {
                $table->text('ghi_chu_thanh_toan')->nullable();
            }
            
            // Cập nhật ràng buộc khóa ngoại
            if (Schema::hasColumn('booking', 'id_kh')) {
                $table->bigInteger('id_kh')->unsigned()->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            // Xóa các cột liên quan đến thanh toán
            $table->dropColumn([
                'phuong_thuc_thanh_toan',
                'trang_thai_thanh_toan', 
                'ma_giao_dich',
                'thoi_gian_thanh_toan',
                'ghi_chu_thanh_toan'
            ]);
            
            // Xóa các cột thông tin khách hàng
            $table->dropColumn([
                'Ten_KH',
                'SDT',
                'Email',
                'Ghi_chu',
                'Tong_tien'
            ]);
            
            // Khôi phục ràng buộc khóa ngoại
            $table->bigInteger('id_kh')->unsigned()->change();
        });
    }
}; 