<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanPhamSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('san_pham')->insert([
            // 1
            [
                'ten_san_pham' => 'Nike Mercurial Vapor 15 Pro TF - DJ5605-601 - Hồng/Đen',
                'gia' =>2350000,
                'mo_ta' => '+ Form giày: Hợp với anh em chân hơi bè hoặc thon. 

                            + Lưỡi gà  co giãn và may liền thân nên giày đi vào ôm sát bàn chân, cảm giác bóng rất thật.

                            + Đế có bộ đệm nên lúc chạy khá êm, ngoài ra độ đàn hồi tốt nên cảm giác lúc bật nhảy hoặc tăng tốc cũng được hỗ trợ phần nào.Tuy nhiên do được chèn bộ đệm nên đế sẽ cao hơn 1 chút xíu so với đế thông thường ,nhưng anh em yên tâm, chỉ cần khoảng 1,2 trận là  sẽ quen đế. Khi đã quen rồi thì đá rất thích.

                            + Các cầu thủ đang đi Nike Mercurial Vapor 15: Vinicius Junior, Robert Lewandowski, Bruno Fernandes…
                            + Nike Vapor 15 pro này nằm trong bộ sưu tập: Nike Mad Brilliance',
                'anh_dai_dien'=>'d1-giay1.png',
                'hot'=>0,
                'view'=> 120,
                'bestseller'=>0,
                'So_luong' => 20,
                'trang_thai' => 1,
                'id_danhmuc'=>1,
                'id_thuonghieu'=>1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // 2
            [
            'ten_san_pham' => 'Nike Zoom Mercurial Superfly 9 Academy MDS TF - Xanh lá FJ7199-300',
            'gia' =>1690000,
            'mo_ta' => '+ Form hợp chân hơi bè hoặc thon
                        + Hỗ trợ sút và rê dắt.',
            'anh_dai_dien'=>'d1-giay2.png',
            'hot'=>1,
            'view'=> 40,
            'bestseller'=>5,
            'So_luong' => 10,
            'trang_thai' => 1,
            'id_danhmuc'=>1,
            'id_thuonghieu'=>1,
            'created_at' => now(),
            'updated_at' => now()
            ],  
            // 3 
            [
                'ten_san_pham' => 'Áo Thun Nam Nike As M Nsw Club Tee AR4999-013 Màu Đen Size M',
                'gia' =>1150000 ,
                'mo_ta' => 'là mẫu áo thun thời trang dành cho nam đến từ thương hiệu nổi tiếng Nike của Mỹ. Mẫu áo được thiết kế năng động trẻ trung,
                 kết hợp chất vải mềm mịn thấm hút tốt, giúp người mặc thấy thoải mái trong mọi hoạt động hàng ngày.',
                'anh_dai_dien'=>'d2-ao1.png',
                'hot'=>0,
                'view'=> 0,
                'bestseller'=>0,
                'So_luong' => 20,
                'trang_thai' => 1,
                'id_danhmuc'=>2 ,
                'id_thuonghieu'=>1,
                'created_at' => now(),
                'updated_at' => now()
                ],      
            // 4
            [
                'ten_san_pham' => 'Áo cầu lông Yonex TPM2898 - Rose Smoke chính hãng ',
                'gia' =>169000 ,
                'mo_ta' => 'Áo cầu lông Yonex TPM2898 - Rose Smoke là sản phẩm chính hãng của thương hiệu Yonex, được thiết kế dành riêng cho người chơi cầu lông.
                 Áo có màu hồng nhạt (Rose Smoke) trang nhã, phù hợp cho cả nam và nữ.',
                'anh_dai_dien'=>'d3-ao2.png',

                'hot'=>0,
                'view'=> 60,
                'bestseller'=>9,
                'So_luong' => 60,
                'trang_thai' => 1,
                'id_danhmuc'=>3 ,
                'id_thuonghieu'=>5 ,
                'created_at' => now(),
                'updated_at' => now()
                ],  
                // 5 
            [
                'ten_san_pham' => 'Vợt Cầu Lông Li-Ning Turbo Charging Marshal Black (4U) AYPU077-4',
                'gia' =>2012727,
                'mo_ta' => 'Vợt cầu lông Turbo Charging Marshal tăng áp được thiết kế dành cho những người chơi đang tìm kiếm hiệu suất vượt trội và sự thoải mái. Cây vợt này có kết cấu nhẹ giúp tăng cường sự nhanh nhẹn và tốc độ trên sân. Công nghệ hấp thụ sốc HDF cải tiến, được tích hợp vào khung, giảm đáng kể tình trạng căng cổ tay và cánh tay, ngăn ngừa chấn thương mà không ảnh hưởng đến những cú đập mạnh mẽ của bạn. Thiết kế đèn pha, kết hợp với hình dạng đầu hình thang, đảm bảo hiệu quả khí động học vượt trội, cho phép những cú đánh nhanh và chính xác. Trục linh hoạt giúp tăng cường khả năng kiểm soát và tính linh hoạt, làm cho Turbocharge Marshal trở thành lựa chọn lý tưởng cho cả người chơi tấn công và phòng thủ.',
                'anh_dai_dien'=>'d5-vot1.png',
                'hot'=>0,
                'view'=> 30,
                'bestseller'=>4,
                'So_luong' => 60,
                'trang_thai' => 1,
                'id_danhmuc'=>5,
                'id_thuonghieu'=>4,
                'created_at' => now(),
                'updated_at' => now()
                ], 
                // 6
            [
                'ten_san_pham' => 'Băng cổ tay 2.5',
                'gia' =>149000,
                'mo_ta' => 'Mã sản phẩm: vỉ: 285050               
                            Màu sắc: Cam, đen, xanh nước biển, xanh navi, hồng, đỏ.
                            Xuất xứ: Đài Loan.
                            Đóng gói: 2chiếc/ túi.
                            Thành phần: 80% cotton,5% Nylon, 5%  Cao su',
                'anh_dai_dien'=>'d6-bang1.png',
                'hot'=>0,
                'view'=> 0,
                'bestseller'=>0,
                'So_luong' => 20,
                'trang_thai' => 1,
                'id_danhmuc'=>6,
                'id_thuonghieu'=>8,
                'created_at' => now(),
                'updated_at' => now()
                ],                                          
       
               // 7 
               [
                'ten_san_pham' => 'Giày Nike Air Max Nuaxis Nam - Trắng Xanh',
                'gia' =>2190000,
                'mo_ta' => ' Giày Nike Air Max Nuaxis là đôi giày lý tưởng cho cuộc sống hàng ngày, mang lại cảm giác thoải mái và phong cách hiện đại. Thiết kế lấy cảm hứng từ Air Max 270,
                             kết hợp cùng bộ đệm Air Max tinh tế, tạo nên một đôi giày vừa êm ái vừa nổi bật, sẵn sàng cùng bạn bước đi mọi lúc',
                'anh_dai_dien'=>'d1-giay3.png',
                'hot'=>0,
                'view'=> 0,
                'bestseller'=>0,
                'So_luong' => 20,
                'trang_thai' => 1,
                'id_danhmuc'=>1,
                'id_thuonghieu'=>1,
                'created_at' => now(),
                'updated_at' => now()
                ],  
                
                // 8
                
                [
                    'ten_san_pham' => 'Giày Nike Run Swift 3 Nam - Xanh Xám',
                    'gia' =>1990000,
                    'mo_ta' => 'Giày Nike Run Swift 3 là mẫu giày được thiết kế cực kỳ đẹp và tinh tế với đặc điểm rất thoáng khí,
                     êm và rất nhẹ. Đây là mẫu giày có thể sử dụng trong mọi hoạt động hàng ngày.',
                    'anh_dai_dien'=>'d1-giay4.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 10,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>1,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],     
                    
                // 9
                
                [
                    'ten_san_pham' => 'Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300',
                    'gia' =>5790000,
                    'mo_ta' => ' Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300 là mẫu giày phổ thông dành cho sân cỏ tự nhiên 11 người. 
                    Phiên bản Phantom GX mang đến sự tươi mới với màu xanh bạc hà (Mint) làm chủ đạo, kết hợp cùng logo màu hồng nổi bật (Atomic Red) và các chi tiết màu xanh navy đậm gần như đen (Atomic Noir), tạo nên sự hài hòa và tinh tế.',
                    'anh_dai_dien'=>'d1-giay5.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 14,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>1,
                    'created_at' => now(),
                    'updated_at' => now()
                ],  
                // 10
                
                [
                    'ten_san_pham' => 'Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300',
                    'gia' =>5790000,
                    'mo_ta' => ' Giày đá bóng Nike Phantom GX II Elite AG-PRO Mad Energy - Mint/Atomic Red/Off Noir FJ2554-300 là mẫu giày phổ thông dành cho sân cỏ tự nhiên 11 người. 
                    Phiên bản Phantom GX mang đến sự tươi mới với màu xanh bạc hà (Mint) làm chủ đạo, kết hợp cùng logo màu hồng nổi bật (Atomic Red) và các chi tiết màu xanh navy đậm gần như đen (Atomic Noir), tạo nên sự hài hòa và tinh tế.',
                    'anh_dai_dien'=>'d1-giay6.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 18,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>1,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],            

                // 11
                
                [
                    'ten_san_pham' => 'Giày Adidas Supernova Prima Nữ - Tím Xanh Ngọc',
                    'gia' =>2790000,
                    'mo_ta' => ' Adidas Supernova Prima luôn sẵn sàng đồng hành cùng bạn. Với thiết kế tối ưu cho sự thoải mái và ổn định, 
                    đôi giày này mang đến cảm giác êm ái và tự tin trên từng bước chạy.',
                    'anh_dai_dien'=>'d1-giay7.png',
                    'hot'=>1,
                    'view'=> 120,
                    'bestseller'=>12,
                    'So_luong' => 18,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>2,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],     
                // 12
                
                [
                    'ten_san_pham' => 'Giày Puma Anzarun 2.0 Open Road Nam - Trắng Cam',
                    'gia' =>1690000,
                    'mo_ta' => ' Giày Puma Anzarun 2.0 Open Road mẫu giày sneaker có thiết kế rất đẹp cùng với những công nghệ cao cấp của Puma.
                     Đây chính là mẫu giày đa năng tuyệt vời cho mọi hoạt động hàng ngày.',
                    'anh_dai_dien'=>'d1-giay8.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>3,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],     

                // 13
                
                [
                    'ten_san_pham' => 'Giày Cầu Lông Yonex Eclipsion Z3 Wide',
                    'gia' =>2519000,
                    'mo_ta' => '  Giày cầu lông Yonex Eclipsion Z3 Wide mang lại tính ổn định và vừa vặn là cốt lõi của dòng sản phẩm ECLIPSION. Dòng sản phẩm này được thiết kế để xử lý ngay cả những động tác chân tiên tiến hoặc phức tạp nhất, 
                    giúp người dùng tự tin trong từng bước và từng bước nhảy.',
                    'anh_dai_dien'=>'d1-giay9.png',
                    'hot'=>0,
                    'view'=> 46,
                    'bestseller'=>11,
                    'So_luong' => 29,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>5,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 11
                
                [
                    'ten_san_pham' => 'Áo cầu lông nam Kamito Hanabi',
                    'gia' =>179000,
                    'mo_ta' => ' Áo cầu lông nam Kamito Hanabi với chất liệu vải cao cấp cùng khả năng thấm hút mồ hôi tốt, sản phẩm là sự lựa chọn tuyệt vời
                     bất chấp mọi điều kiện thời tiết để bạn sử dụng thoải mái ở bất kỳ hoạt động nào như: chơi thể thao (cầu lông, tennis, chạy bộ,...), dã ngoại, sự kiện ngoài trời,...',
                    'anh_dai_dien'=>'d2-ao2.png',
                    'hot'=>1,
                    'view'=> 100,
                    'bestseller'=>12,
                    'So_luong' => 50,
                    'trang_thai' => 1,
                    'id_danhmuc'=>2,
                    'id_thuonghieu'=>7,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 12
                
                [
                    'ten_san_pham' => 'Áo tennis Wilson WN2002-17-01 nam - Xanh đen biển chính hãng',
                    'gia' =>580000,
                    'mo_ta' => ' Áo tennis Wilson WN2002-17-01 nam - Xanh đen biển chính hãng là một trong những mẫu áo tennis chính hãng nổi trội với chất liệu vải thấm hút tốt, mát mẻ, form áo đẹp, 
                    màu sắc bắt mắt và đặc biệt là có giá thành phải chăng đảm bảo sẽ làm các tay vợt cực ưng ý ngay từ lần đầu tiên sử dụng.',
                    'anh_dai_dien'=>'d2-ao3.png',
                    'hot'=>1,
                    'view'=> 150,
                    'bestseller'=>40,
                    'So_luong' => 80,
                    'trang_thai' => 1,
                    'id_danhmuc'=>2,
                    'id_thuonghieu'=>6,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],   
                // 13
                
                [
                    'ten_san_pham' => 'Áo T-shirt Nam AHSU533-3V',
                    'gia' =>775000,
                    'mo_ta' => 'Áo T-shirt Nam AHSU533-3V
                        Chất liệu: 56% modal 44% polyester
                        Dòng sản phẩm: Thời Trang/Sportlife
                        Form dáng: Loose fit
                        Xuất xứ: Chính hãng ',
                    'anh_dai_dien'=>'d2-ao4.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>2,
                    'id_thuonghieu'=>4,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],    
                // 14
                
                [
                    'ten_san_pham' => 'Áo HEAD CLUB 22 Tech Polo Shirt M',
                    'gia' =>790000,
                    'mo_ta' => 'Club 22 tech Polo được được thiết kế với công nghệ hút ẩm tiên tiến của HEAD mang lại hiệu quả làm mát và cho phép chất liệu khô nhanh chóng.
                                Thiết kế cổ bẻ không chỉ mang lại vẻ ngoài nam tính, lịch thiệp mà còn rất trẻ trung và phóng khoáng. 
                                Logo head được thiết kế in đằng trước ngực sang trọng và tinh tế.',
                    'anh_dai_dien'=>'d3-ao2.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>3,
                    'id_thuonghieu'=>8,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 15
                
                [
                    'ten_san_pham' => 'Áo polo Kamito Master được thiết kế dành riêng cho những ai yêu thích sự đơn giản 
                    nhưng vẫn muốn nổi bật. Với phom dáng thoải mái, chất liệu thoáng mát, chiếc áo này giúp bạn tự do di chuyển, từ tập luyện đến các hoạt động thường ngày.',
                    'gia' =>390000,
                    'mo_ta' => ' ',
                    'anh_dai_dien'=>'d3-ao3.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>3,
                    'id_thuonghieu'=>7,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 16
                
                [
                    'ten_san_pham' => 'Áo Polo Nam APLU119-1V',
                    'gia' =>932000,
                    'mo_ta' => 'Áo Polo Nam APLU119-1V

                        Chất liệu vải : Polyester100%
                        Công nghệ :AT DRY ULTRA
                        Dòng sản phẩm :Fitness/Luyện tập
                        Form dáng :Regular Fit
                        Xuất xứ: Trung Quốc
                        Form Chọn Size : Châu Á',
                    'anh_dai_dien'=>'d3-ao4.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>3,
                    'id_thuonghieu'=>4,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],

                // 17
                
                [
                    'ten_san_pham' => 'Giày Nike Air Max Nuaxis Nam - Trắng Xanh',
                    'gia' =>399000,
                    'mo_ta' => 'Áo Polo Kamito Artista mang đến phong cách hiện đại và đầy lịch lãm – lựa chọn lý tưởng cho mọi hoạt động thường ngày, 
                    để bạn tự do thể hiện cá tính và phong cách riêng. ',
                    'anh_dai_dien'=>'d3-ao5.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>1,
                    'id_thuonghieu'=>4,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],

                 // 18            
                [
                    'ten_san_pham' => 'Quần Ngắn Nike Woven Flow Shorts',
                    'gia' =>360000,
                    'mo_ta' => '- Lớp vải Poly mang đến độ bền cao cùng với khả năng kháng nước cực tốt cho người mặc.
                    - Kiểu dáng vừa vặn không quá ôm sát cơ thể, tạo cảm giác thoải mái.
                    - Thiết kế đơn giản nhưng trẻ trung và tinh tế, là sự lựa chọn hoàn hảo cho những buổi dạo phố và kể cả nhưng buổi tập thể thao. ',
                    'anh_dai_dien'=>'d4-quan1quan1.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>4,
                    'id_thuonghieu'=>1,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],  
                // 19
                
                [
                    'ten_san_pham' => 'Quần ngắn Nike Big Swoosh Repeat Shorts',
                    'gia' =>450000,
                    'mo_ta' => 'Quần ngắn Nike Big Swoosh Repeat Shorts là sản phẩm thể thao nổi bật với thiết kế logo Swoosh lặp lại,
                     mang lại phong cách năng động và hiện đại. ',
                    'anh_dai_dien'=>'d4-quan2.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>4,
                    'id_thuonghieu'=>1,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 20
                
                [
                    'ten_san_pham' => 'Quần Ngắn Nữ Adidas Own The Run Bike - Hồng',
                    'gia' =>575000,
                    'mo_ta' => 'Hãy thúc đẩy bản thân chinh phục đường chạy với chiếc quần short adidas siêu nhẹ này. Với công nghệ AEROREADY thấm hút ẩm giúp bạn luôn khô ráo trên từng dặm đường, bạn sẽ tự tin làm chủ về cả cự ly và tốc độ.
                     Các túi khóa kéo giữ chắc chìa khóa hoặc thanh năng lượng để tiện lấy khi di chuyển. ',
                    'anh_dai_dien'=>'d4-quan3.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>4,
                    'id_thuonghieu'=>2,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 21
                
                [
                    'ten_san_pham' => 'Quần Ngắn Nữ Adidas Own The Run - Xanh Dương',
                    'gia' =>600000,
                    'mo_ta' => 'Tự tin về đích với Quần Ngắn Nữ Adidas Own The Run siêu nhẹ! Công nghệ AEROREADY thấm hút ẩm giúp bạn luôn khô ráo trên từng dặm đường, tự tin làm chủ về cả cự ly và tốc độ.
                     Các túi khóa kéo giữ chắc chìa khóa hoặc thanh năng lượng để tiện lấy khi di chuyển.  ',
                    'anh_dai_dien'=>'d4-quan4.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>4,
                    'id_thuonghieu'=>2,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 22
                
                [
                    'ten_san_pham' => 'Quần thể thao puma running 5 inch active woven "brown" 576728 - hàng chính hãng',
                    'gia' =>350000,
                    'mo_ta' => ' ',
                    'anh_dai_dien'=>'d4-quan5.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>4,
                    'id_thuonghieu'=>3,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 23
                
                [
                    'ten_san_pham' => 'Quần thời trang puma drycell pants black 519500-01 - hàng chính hãng',
                    'gia' =>350000,
                    'mo_ta' => 'Puma Drycell Pants Black 519500-01 Quần Thời Trang Puma Drycell Pants "Black" 519500-01 là sự kết hợp hoàn hảo giữa phong cách thời trang và tính năng thể thao. Với công nghệ Drycell tiên tiến, 
                    quần giúp thấm hút mồ hôi hiệu quả, giữ cho cơ thể luôn khô ráo và thoải mái. ',
                    'anh_dai_dien'=>'d4-quan6.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>4,
                    'id_thuonghieu'=>3,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 24
                
                [
                    'ten_san_pham' => 'Vợt tennis Wilson Triad Five',
                    'gia' =>5750000,
                    'mo_ta' => 'Vợt tennis Wilson Triad Five (Mã: WR056611U2 ) là dòng vợt Power and Comfort (trợ lực và tạo cảm giác thoải mái), công nghệ Triad tạo cho người đánh có cảm giác thoải mái nhất với mặt vợt trung bình 103" và cảm giác mềm nhẹ nhàng khi đánh.
                    Vợt dành cho các bạn tìm khung vợt trợ lực vửa phải , mặt vợt đủ nhỏ để có thể kiểm soát bóng + nhiều lực và phải xoáy.
                    Khung vợt được thiết kế khí động học rất khác biệt + làm tăng lực đánh, nhẹ nhàng và linh hoạt hơn trong và có khả năng chịu lực đánh bóng nặng. ',
                    'anh_dai_dien'=>'d5-tennis1.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>6,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 25
                
                [
                    'ten_san_pham' => 'Vợt tennis Wilson Roland Garros Elite 21',
                    'gia' =>1200000,
                    'mo_ta' => 'Vợt tennis Wilson Roland Garros Elite 21 (Mã: WR029610H ) dành cho trẻ em độ tuổi từ 4-6 tuổi. Vợt được thiết kế với các công nghệ mới tạo thành dòng vợt dễ đánh, dễ vung vợt, độ ổn định cao.
                     Thiết kế màu sắc theo giải GRAND SLAM ROLAND GARROS ',
                    'anh_dai_dien'=>'d5-tennis2.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>6,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 26
                
                [
                    'ten_san_pham' => 'QUẢ BÓNG ĐÁ KAMITO ARTISTA',
                    'gia' =>1799000,
                    'mo_ta' => 'Bóng Kamito Artista được lấy cảm hứng từ phong cách chơi bóng kỹ thuật của Danh thủ Nguyễn Hồng Sơn, mang đến trải nghiệm thi đấu tuyệt vời, giúp cầu thủ luôn thăng hoa trong mọi trận đấu. Bóng Kamito Artista, được thiết kế với công nghệ tiên tiến,
                     đạt tiêu chuẩn FIFA PRO đáp ứng tối ưu cho thi đấu bóng đá chuyên nghiệp trên sân 7, và sân 11 người. ',
                    'anh_dai_dien'=>'d5-bong1.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>7,
                    'created_at' => now(),
                    'updated_at' => now()
                    ], 
                // 27
                
                [
                    'ten_san_pham' => 'QUẢ BÓNG ĐÁ KAMITO LEGEND',
                    'gia' =>1199000,
                    'mo_ta' => 'Quả bóng đá Kamito Legend, được thiết kế với công nghệ tiên tiến, 
                    đáp ứng tiêu chuẩn FIFA PRO dành cho bóng đá chuyên nghiệp sân 11 người. ',
                    'anh_dai_dien'=>'d5-bong2.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>7,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 28
                
                [
                    'ten_san_pham' => 'Wilson Vợt Pickleball Cadence Edgeless 16 PB WR181011U2  ',
                    'gia' =>5209000,
                    'mo_ta' => 'Wilson Vợt Pickleball Cadence Edgeless 16 PB WR181011U2
                    Được thiết kế cho những người chơi cạnh tranh, có khả năng đặt bóng chính xác và mong muốn hỗ trợ thêm sức mạnh cho các cú đánh của mình. Vợt có thiết kế không viền, giúp cải thiện tính khí động học, tăng khả năng linh hoạt cho các cú vô lê gần lưới. Bề mặt sợi carbon thô tạo độ xoáy ,
                    trong khi lõi tổ ong polypropylene dày 16mm giúp hỗ trợ giảm rung chấn và mang lại cảm giác nhất quán. ',
                    'anh_dai_dien'=>'d5-pickleball1.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>6,
                    'created_at' => now(),
                    'updated_at' => now()
                    ], 
                // 29
                
                [
                    'ten_san_pham' => 'Wilson Fierce Team Pickleball Paddle Purple Vợt Pickleball WR161011U2',
                    'gia' =>2089000,
                    'mo_ta' => 'Vợt dành cho người mới bắt đầu và người chơi thỉnh thoảng đang tìm kiếm cây vợt pickleball đầu tiên của mình, Wilsons Fierce Team giúp bạn vào sân và chơi một cách tự tin trong thời gian ngắn. Hình dạng lai của nó kết hợp những điểm tốt của cả hai loại vợt, mang đến cho bạn khả năng kiểm soát và điểm ngọt lớn hơn của thân vợt rộng và sức mạnh bổ sung của một cây vợt dài. Lõi tổ ong polypropylene làm giảm độ rung và tăng cường cảm giác,
                     và mặt vợt bằng sợi thủy tinh 13 mm giúp bạn thực hiện những cú đánh thoải mái, nhất quán.',
                    'anh_dai_dien'=>'d5-pickleball2.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>6,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                // 30
                
                [
                    'ten_san_pham' => 'Vợt cầu lông Yonex Nanoflare Junior',
                    'gia' =>1419000,
                    'mo_ta' => 'Vợt cầu lông Yonex Nanoflare Junior được thiết kế cho lối chơi tốc độ, linh hoạt giữa công và thủ với điểm cân bằng ở mức cân bằng. Đũa vợt siêu dẻo mang lại khả năng trợ lực một cách tối ưu, trọng lượng 4U không quá nặng,
                     thích hợp cho những người mới bắt đầu tập làm quen với bộ môn này hoặc các lông thủ nhí. ',
                    'anh_dai_dien'=>'d5-vot2.png',
                    'hot'=>0,
                    'view'=> 0,
                    'bestseller'=>0,
                    'So_luong' => 20,
                    'trang_thai' => 1,
                    'id_danhmuc'=>5,
                    'id_thuonghieu'=>5,
                    'created_at' => now(),
                    'updated_at' => now()
                    ],
                    // 31
                    [
                        'ten_san_pham' => 'Băng Đô, Băng Trán Thể Thao Adidas CF6926',
                        'gia' => 190000,
                        'mo_ta' => 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn',
                        'anh_dai_dien'=>'d6-bangdo1.png',
                        'hot'=>0,
                        'view'=> 0,
                        'bestseller'=>0,
                        'So_luong' => 20,
                        'trang_thai' => 1,
                        'id_danhmuc'=>6,
                        'id_thuonghieu'=>2,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // 32
                    [
                        'ten_san_pham' => 'Bộ 3 Đôi Tất Cổ Cao Lót Đệm 3 Sọc',
                        'gia' => 180000,
                        'mo_ta' => 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn',
                        'anh_dai_dien'=>'d6-vo1.png',
                        'hot'=>0,
                        'view'=> 0,
                        'bestseller'=>0,
                        'So_luong' => 20,
                        'trang_thai' => 1,
                        'id_danhmuc'=>6,
                        'id_thuonghieu'=>2,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // 33
                    [
                        'ten_san_pham' => 'Nón Thể Thao Unisex NIKE Dri-Fit Club Unstructured Featherlight FB5682-100',
                        'gia' =>290000,
                        'mo_ta' => 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn',
                        'anh_dai_dien'=>'d6-non1.png',
                        'hot'=>0,
                        'view'=> 0,
                        'bestseller'=>0,
                        'So_luong' => 20,
                        'trang_thai' => 1,
                        'id_danhmuc'=>6,
                        'id_thuonghieu'=>1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    // 34
                    [
                        'ten_san_pham' => 'Túi Xách Tập Luyện Unisex NIKE Nike Heritage DB0490-010',
                        'gia' => 180000,
                        'mo_ta' => 'Thương hiệu: Adidas; Chất liệu: Vải dệt kim trơn',
                        'anh_dai_dien'=>'d6-tuideo1.png',
                        'hot'=>0,
                        'view'=> 0,
                        'bestseller'=>0,
                        'So_luong' => 20,
                        'trang_thai' => 1,
                        'id_danhmuc'=>6,
                        'id_thuonghieu'=>1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
         
        ]); 
    }
}
