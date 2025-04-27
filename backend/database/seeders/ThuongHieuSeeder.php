<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThuongHieuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('thuong_hieu')->insert([
            // 1
            [
                'id' => 1,
                'ten_thuong_hieu' => 'Nike',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 2
            [
                'id' => 2,
                'ten_thuong_hieu' => 'Adidas',
               
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 3
            [
                'id' => 3,
                'ten_thuong_hieu' => 'Puma',
               
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 4
            [
                'id' => 4,
                'ten_thuong_hieu' => 'L-ning',
               
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 5 
            [
                'id' => 5,
                'ten_thuong_hieu' => 'Yonex',
               
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 6 
            [
                'id' => 6,
                'ten_thuong_hieu' => 'Wilson',
               
                'created_at' => now(),
                'updated_at' => now()
            ],
             // 7 
             [
                'id' => 7,
                'ten_thuong_hieu' => 'Kamito',
               
                'created_at' => now(),
                'updated_at' => now()
            ],  
             // 8 
             [
                'id' => 8,
                'ten_thuong_hieu' => 'Head',
               
                'created_at' => now(),
                'updated_at' => now()
            ],
            
         
        ]);
    }
}
