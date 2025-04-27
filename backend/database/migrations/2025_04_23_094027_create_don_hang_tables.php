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
        // Kiểm tra xem bảng đã tồn tại chưa
        if (!Schema::hasTable('don_hang')) {
            Schema::create('don_hang', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_kh');  // Tham chiếu đến bảng users
                $table->unsignedBigInteger('ID_Khuyenmai')->nullable(); // Tham chiếu đến bảng khuyến mãi
                $table->dateTime('ngay_dat')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->string('trang_thai')->default('Chờ xác nhận');
                $table->string('phuong_thuc_tt')->default('cod');
                $table->string('trang_thai_tt')->default('pending');
                $table->string('dia_chi_giao_hang');
                $table->string('ma_don_hang');
                $table->decimal('tong_tien', 15, 0)->default(0);
                $table->timestamps();
            });
            
            // Log tạo bảng thành công
            echo "Đã tạo bảng don_hang thành công\n";
        } else {
            echo "Bảng don_hang đã tồn tại\n";
        }

        // Kiểm tra xem bảng chi tiết đã tồn tại chưa
        if (!Schema::hasTable('don_hang_chi_tiet')) {
            Schema::create('don_hang_chi_tiet', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_dh'); // Tham chiếu đến bảng đơn hàng
                $table->unsignedBigInteger('id_sp'); // Tham chiếu đến bảng sản phẩm
                $table->integer('so_luong')->default(1);
                $table->decimal('don_gia', 15, 0);
                $table->decimal('thanh_tien', 15, 0);
                $table->unsignedBigInteger('id_mau')->nullable(); // Tham chiếu đến bảng màu sắc
                $table->unsignedBigInteger('id_size')->nullable(); // Tham chiếu đến bảng kích thước
                $table->timestamps();
            });
            
            // Log tạo bảng thành công
            echo "Đã tạo bảng don_hang_chi_tiet thành công\n";
        } else {
            echo "Bảng don_hang_chi_tiet đã tồn tại\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang_chi_tiet');
        Schema::dropIfExists('don_hang');
    }
};
