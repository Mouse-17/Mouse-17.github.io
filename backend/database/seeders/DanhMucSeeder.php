<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhMucSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('danh_muc')->insert([
            // 1
            [   
                'id' => 1,
                'ten_danh_muc' => 'Giày',
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 2
            [
                'id' => 2,
                'ten_danh_muc' => 'Áo thun ',
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
             // 3 
             [
                'id' => 3,
                'ten_danh_muc' => 'Áo Polo',
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
             // 4 
             [
                'id' => 4,
                'ten_danh_muc' => 'Quần', 
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
             // 5 
             [
                'id' => 5,
                'ten_danh_muc' => 'Dụng cụ thể thao',
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
             // 6
             [
                'id' => 6,
                'ten_danh_muc' => 'Phụ kiện khác',
                'trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
         
        ]);
    }
}
