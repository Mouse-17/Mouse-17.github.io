<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class Khung_GioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('khung_gio')->count() == 0) {
            DB::table('khung_gio')->insert([
                [
                    'Gio_bat_dau' => '06:00:00',
                    'Gio_ket_thuc' => '08:00:00',
                    'Gia_thue' => 200000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'Gio_bat_dau' => '08:00:00',
                    'Gio_ket_thuc' => '10:00:00',
                    'Gia_thue' => 250000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'Gio_bat_dau' => '10:00:00',
                    'Gio_ket_thuc' => '12:00:00',
                    'Gia_thue' => 300000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'Gio_bat_dau' => '14:00:00',
                    'Gio_ket_thuc' => '16:00:00',
                    'Gia_thue' => 300000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'Gio_bat_dau' => '16:00:00',
                    'Gio_ket_thuc' => '18:00:00',
                    'Gia_thue' => 350000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'Gio_bat_dau' => '18:00:00',
                    'Gio_ket_thuc' => '20:00:00',
                    'Gia_thue' => 400000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
