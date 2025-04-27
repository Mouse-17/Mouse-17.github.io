<?php

namespace App\Http\Controllers;

use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThongBaoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);
            }

            $notifications = ThongBao::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $unreadCount = ThongBao::where('user_id', $userId)
                ->where('da_xem', 0)
                ->count();

            return response()->json([
                'status' => 'success',
                'data' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy thông báo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $notification = ThongBao::findOrFail($id);
            
            // Kiểm tra quyền
            if (Auth::id() !== $notification->user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn không có quyền đánh dấu thông báo này'
                ], 403);
            }
            
            $notification->update(['da_xem' => 1]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Đã đánh dấu đã đọc'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi đánh dấu đã đọc: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);
            }
            
            ThongBao::where('user_id', $userId)
                ->where('da_xem', 0)
                ->update(['da_xem' => 1]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Đã đánh dấu tất cả thông báo là đã đọc'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi đánh dấu tất cả thông báo đã đọc: ' . $e->getMessage()
            ], 500);
        }
    }
}