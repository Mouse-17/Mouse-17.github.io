<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gọi các seeder khác
        $this->call([
            Khung_GioSeeder::class,
            KhachHangSeeder::class,
            SanSeeder::class,
        ]);
        
        // Thêm dữ liệu vào bảng booking
        DB::table('booking')->insert([
            // Booking 1
            [
                'id_kh' => 1,
                'id_san' => 1,
                'id_kg' => 1,
                'Ngay_dat' => Carbon::now()->addDays(1)->toDateString(),
                'Trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Booking 2
            [
                'id_kh' => 2,
                'id_san' => 2,
                'id_kg' => 2,
                'Ngay_dat' => Carbon::now()->addDays(2)->toDateString(),
                'Trang_thai' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Booking 3
            [
                'id_kh' => 3,
                'id_san' => 3,
                'id_kg' => 3,
                'Ngay_dat' => Carbon::now()->addDays(3)->toDateString(),
                'Trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Booking 4
            [
                'id_kh' => 1,
                'id_san' => 4,
                'id_kg' => 4,
                'Ngay_dat' => Carbon::now()->addDays(4)->toDateString(),
                'Trang_thai' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Booking 5
            [
                'id_kh' => 2,
                'id_san' => 1,
                'id_kg' => 2,
                'Ngay_dat' => Carbon::now()->addDays(5)->toDateString(),
                'Trang_thai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]); 
    }

}
