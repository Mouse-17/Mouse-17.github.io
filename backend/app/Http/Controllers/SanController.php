<?php

namespace App\Http\Controllers;
use App\Models\San;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SanController extends Controller
{
    public function showListYard(Request $request){
        try {
            // Lấy các tham số từ request
            $tukhoa = $request->input('tukhoa');
            $sapXep = $request->input('sap_xep');
            $loaiSan = $request->input('loai_san');
            $danhGia = $request->input('danh_gia');
            $thoiGianBatDau = $request->input('thoi_gian_bat_dau');
            $thoiGianKetThuc = $request->input('thoi_gian_ket_thuc');
            $giaMin = $request->input('gia_min');
            $giaMax = $request->input('gia_max');
            $ngay = $request->input('ngay');
            $gia = $request->input('gia');
            
            // Lấy danh sách sân cùng với đánh giá trung bình
            $query = San::leftJoin('danh_gia_san', 'san.id', '=', 'danh_gia_san.ID_San')
                ->select(
                    'san.*', 
                    DB::raw('COALESCE(AVG(danh_gia_san.So_sao), 0) as diem_danh_gia'),
                    DB::raw('COUNT(danh_gia_san.ID_San) as so_luot_danh_gia')
                )
                ->groupBy('san.id');
            
            // Lọc theo từ khóa
            if (!empty($tukhoa)) {
                $query->where(function($q) use ($tukhoa) {
                    $q->where('Ten_san', 'like', '%' . $tukhoa . '%')
                      ->orWhere('Mo_ta', 'like', '%' . $tukhoa . '%')
                      ->orWhere('Dia_chi', 'like', '%' . $tukhoa . '%');
                });
            }
            
            // Lọc theo loại sân
            if (!empty($loaiSan)) {
                $query->where('ID_Loai', $loaiSan);
            }
            
            // Lọc theo đánh giá
            if (!empty($danhGia) && $danhGia > 0) {
                $query->havingRaw('COALESCE(AVG(danh_gia_san.So_sao), 0) = ?', [$danhGia]);
            }
            
            // Lọc theo khung giờ
            if (!empty($thoiGianBatDau) && !empty($thoiGianKetThuc)) {
                $query->whereRaw("TIME(Thoi_gian_hoat_dong) >= ?", [$thoiGianBatDau])
                      ->whereRaw("TIME(Thoi_gian_hoat_dong) <= ?", [$thoiGianKetThuc]);
            }
            
            // Lọc theo khoảng giá
            if (!empty($giaMin)) {
                $query->where('Gia', '>=', $giaMin);
            }
            
            if (!empty($giaMax)) {
                $query->where('Gia', '<=', $giaMax);
            }
            
            // Sắp xếp theo giá
            if (!empty($gia)) {
                if ($gia === 'cao_thap') {
                    $query->orderBy('Gia', 'desc');
                } elseif ($gia === 'thap_cao') {
                    $query->orderBy('Gia', 'asc');
                }
            }
            
            // Sắp xếp theo tiêu chí khác
            if (!empty($sapXep)) {
                if ($sapXep === 'moi_nhat') {
                    $query->orderBy('san.created_at', 'desc');
                } elseif ($sapXep === 'cu_nhat') {
                    $query->orderBy('san.created_at', 'asc');
                } elseif ($sapXep === 'bestseller') {
                    $query->orderBy('bestseller', 'desc');
                } elseif ($sapXep === 'danh_gia') {
                    $query->orderBy('diem_danh_gia', 'desc');
                }
            } else {
                // Mặc định sắp xếp theo bestseller và hot
                $query->orderBy('bestseller', 'desc')->orderBy('hot', 'desc');
            }
            
            // Paginate kết quả
            $yard = $query->paginate(9);
            
            // Format lại diem_danh_gia để làm tròn đến 1 chữ số thập phân
            $yards = $yard->items();
            foreach ($yards as $item) {
                $item->diem_danh_gia = round($item->diem_danh_gia, 1);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $yards,
                'pagination' => [
                    'current_page' => $yard->currentPage(),
                    'last_page' => $yard->lastPage(),
                    'per_page' => $yard->perPage(),
                    'total' => $yard->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $yarddetail = San::leftJoin('danh_gia_san', 'san.id', '=', 'danh_gia_san.ID_San')
                ->select(
                    'san.*', 
                    DB::raw('COALESCE(AVG(danh_gia_san.So_sao), 0) as diem_danh_gia'),
                    DB::raw('COUNT(danh_gia_san.ID_San) as so_luot_danh_gia')
                )
                ->where('san.id', $id)
                ->groupBy('san.id')
                ->first();
                
            if (!$yarddetail) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sân'
                ], 404);
            }
            
            // Format lại diem_danh_gia để làm tròn đến 1 chữ số thập phân
            $yarddetail->diem_danh_gia = round($yarddetail->diem_danh_gia, 1);
            
            return response()->json([
                'status' => 'success',
                'data' => $yarddetail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm kiếm sân theo từ khóa
     */
    public function timkiemSan(Request $request)
    {
        try {
            $tukhoa = $request->input('tukhoa');
            
            if (empty($tukhoa)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng nhập từ khóa tìm kiếm'
                ], 400);
            }
            
            $query = San::leftJoin('danh_gia_san', 'san.id', '=', 'danh_gia_san.ID_San')
                ->select(
                    'san.*', 
                    DB::raw('COALESCE(AVG(danh_gia_san.So_sao), 0) as diem_danh_gia'),
                    DB::raw('COUNT(danh_gia_san.ID_San) as so_luot_danh_gia')
                )
                ->where(function($q) use ($tukhoa) {
                    $q->where('Ten_san', 'like', '%' . $tukhoa . '%')
                      ->orWhere('Mo_ta', 'like', '%' . $tukhoa . '%')
                      ->orWhere('Dia_chi', 'like', '%' . $tukhoa . '%');
                })
                ->groupBy('san.id');
            
            $ketqua = $query->paginate(9);
            
            // Format lại diem_danh_gia để làm tròn đến 1 chữ số thập phân
            $items = $ketqua->items();
            foreach ($items as $item) {
                $item->diem_danh_gia = round($item->diem_danh_gia, 1);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $items,
                'pagination' => [
                    'current_page' => $ketqua->currentPage(),
                    'last_page' => $ketqua->lastPage(),
                    'per_page' => $ketqua->perPage(),
                    'total' => $ketqua->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tìm kiếm sân: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tất cả đánh giá của một sân
     */
    public function getSanRatings($id)
    {
        try {
            // Kiểm tra sân tồn tại
            $san = San::find($id);
            
            if (!$san) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sân'
                ], 404);
            }
            
            // Lấy đánh giá
            $ratings = DB::table('danh_gia_san')
                ->leftJoin('users', 'danh_gia_san.ID_KH', '=', 'users.id')
                ->select(
                    'danh_gia_san.*',
                    'users.name',
                    'users.avatar'
                )
                ->where('danh_gia_san.ID_San', $id)
                ->orderBy('danh_gia_san.created_at', 'desc')
                ->get();
            
            // Tính điểm trung bình
            $averageRating = 0;
            if ($ratings->count() > 0) {
                $averageRating = $ratings->avg('So_sao');
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $ratings,
                'average' => round($averageRating, 1),
                'count' => $ratings->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy danh sách khung giờ và giá
     */
    public function getTimeSlots()
    {
        try {
            $timeSlots = DB::table('khung_gio')
                ->select('id', 'Gio_bat_dau', 'Gio_ket_thuc', 'Gia_thue')
                ->orderBy('Gio_bat_dau', 'asc')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $timeSlots
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
