<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Loai_SanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loai_san')->insert([
            // 1
            [   'id' => 1,
                'Ten_loai' => 'Sân trong nhà',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân thể thao trong nhà là công trình được thiết kế khép kín, với mái che và hệ thống tường bao nhằm đảm bảo hoạt động thể thao diễn ra ổn định, không bị ảnh hưởng bởi điều kiện thời tiết. Không gian bên trong được bố trí linh hoạt để phục vụ nhiều bộ môn. ',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 2
            [   'id' => 2,
                'Ten_loai' => 'Sân ngoài trời',
                'Trang_thai' => 1,
                'Mo_ta' => 'Sân thể thao ngoài trời là khu vực không gian mở được thiết kế và xây dựng để phục vụ các hoạt động thể dục thể thao. Sân thường được bố trí ở vị trí thoáng đãng, có hệ thống mặt sân được xử lý kỹ lưỡng bằng các vật liệu chuyên dụng như bê tông, thảm cỏ nhân tạo, cao su tổng hợp hoặc sơn phủ epoxy chống trượt, phù hợp với từng môn thể thao cụ thể.',
                'created_at' => now(),
                'updated_at' => now()
            ],      
        
        ]);
    }
}
