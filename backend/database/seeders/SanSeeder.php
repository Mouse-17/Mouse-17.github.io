<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('san')->insert([
            [
                'Ten_san' => 'Sân cầu lông Nhật Nam',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân cầu lông tiêu chuẩn, có máy lạnh',
                'Dia_chi' => '143/12 Phan Huy Ích, Phường 15, Quận Gò vấp, Tp.HCM',
                'Hinh_anh' => 's1-sancaulong1.png',
                'ID_Loai' =>1,
                'hot' => 1,
                'view' => 182,
                'bestseller' => 10,
                'So_luong' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Ten_san' => 'Sân tennis Khang An ',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân tennis trong nhà, mặt sân cứng',
                'Dia_chi' => '18A Phan Văn Trị, Phường 10 , Quận Gò Vấp , Tp Hồ Chí Minh.',
                'Hinh_anh' => 's1-santennis2.png',
                'ID_Loai' => 1,
                'hot' => 0,
                'view' => 83,
                'bestseller' => 0,
                'So_luong' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Ten_san' => 'Sân cầu lông Bảo Hà',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân cầu lông trong nhà,  ánh sáng tốt',
                'Dia_chi' => '1d Đ. Nguyễn Văn Lượng, Phường 6, Gò Vấp, TP HCM',
                'Hinh_anh' => 's1-sancaulong2.png',
                'ID_Loai' => 1,
                'hot' => 0,
                'view' => 0,
                'bestseller' => 0,
                'So_luong' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Ten_san' => 'Sân bóng đá AN PHÚ ARENA',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân bóng đá cỏ nhân tạo, có đèn chiếu sáng',
                'Dia_chi' => '306 Võ Văn Hát, Long Trường, Quận 9, TP HCM',
                'Hinh_anh' => 's1-sanbongda1.png',
                'ID_Loai' => 1,
                'hot' => 0,
                'view' => 9,
                'bestseller' => 0,
                'So_luong' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ['Ten_san' => 'Sân tennis Gò Vấp Bệnh Viện 175',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân tennis ngoài trời, mặt sân cứng',
                'Dia_chi' => '786B Nguyễn Kiệm, P.3, Quận Gò Vấp, TP HCM',
                'Hinh_anh' => 's2-santennis2.png',
                'ID_Loai' => 2,
                'hot' => 0,
                'view' => 82,
                'bestseller' => 0,
                'So_luong' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Ten_san' => 'Sân bóng đá cỏ nhân tạo VOV',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân bóng đá ngoài trời, sân cỏ nhân tạo',
                'Dia_chi' => 'Hẻm 38/89 Đông Hưng Thuận 11, P. Đông Hưng Thuận, Q.12, TP HCM',
                'Hinh_anh' => 's2-sanbongda2.png',
                'ID_Loai' => 2,
                'hot' => 0,
                'view' => 15,
                'bestseller' => 0,
                'So_luong' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Ten_san' => 'Sân golf Tân Sơn Nhất',
                'Trang_thai' => 1,
                'Mo_ta' => 'Tan Son Nhat Golf Course là một trong những sân golf ở thành phố Hồ Chí Minh được nhiều golfer biết đến,
                    là địa chỉ quen thuộc của golf thủ phía Nam',
                'Dia_chi' => 'số 6 đường Tân Sơn, P. 12, Q. Gò Vấp, TP HCM',
                'Hinh_anh' => 's2-sangolf1.png',
                'ID_Loai' => 2,
                'hot' => 0,
                'view' => 12,
                'bestseller' => 0,
                'So_luong' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Ten_san' => 'Sân golf TPHCM Him Lam Driving Range',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân golf Him Lam driving range trở thành điểm đến lý tưởng của nhiều golfer phía Nam.
                    Sở hữu khung cảnh đẹp ngay bên bờ sông Sài Gòn',
                'Dia_chi' => '234 đường Ngô Tất Tố, P. 22, Q. Bình Thạnh, TP HCM',
                'Hinh_anh' => 's2-sangolf2.png',
                'ID_Loai' => 2,
                'hot' => 0,
                'view' => 34,
                'bestseller' => 0,
                'So_luong' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}