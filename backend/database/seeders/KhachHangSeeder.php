<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class KhachHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('khach_hang')->count() == 0) {
            DB::table('khach_hang')->insert([
                [
                    'ho_ten' => 'Nguyễn Văn A',
                    'email' => 'nguyenvana@example.com',
                    'so_dien_thoai' => '0901234567',
                    'password' => '123',
                    'dia_chi' => 'Quận 1, TP HCM',
                    'vai_tro' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'ho_ten' => 'Trần Thị B',
                    'email' => 'tranthib@example.com',
                    'so_dien_thoai' => '0912345678',
                    'password' => '123',
                    'dia_chi' => 'Quận 2, TP HCM',
                    'vai_tro' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'ho_ten' => 'Lê Văn C',
                    'email' => 'levanc@example.com',
                    'so_dien_thoai' => '0923456789',
                    'password' => '123',
                    'dia_chi' => 'Quận 3, TP HCM',
                    'vai_tro' => '0',        
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'ho_ten' => 'Admin',
                    'email' => 'admin@example.com',
                    'so_dien_thoai' => '0987654321',
                    'password' => '123', 
                    'dia_chi' => 'Quận 4, TP HCM',
                    'vai_tro' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
