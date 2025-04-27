<?php

namespace App\Http\Controllers;

use App\Models\Loai_san;
use Illuminate\Http\Request;

class LoaiSanController extends Controller
{
    /**
     * Lấy danh sách loại sân đang hoạt động
     */
    public function index()
    {
        try {
            $loai_san = Loai_san::where('Trang_thai', 1)->get();
            return response()->json([
                'status' => 'success',
                'data' => $loai_san
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách loại sân: ' . $e->getMessage()
            ], 500);
        }
    }
} 