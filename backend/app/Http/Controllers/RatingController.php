<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Hiển thị danh sách đánh giá.
     */
    public function index()
    {
        try {
            $ratings = Rating::with([
                'user:id,name',         // Chỉ lấy cột cần thiết từ bảng users
                'product:id,name',      // Chỉ lấy cột cần thiết từ bảng sản phẩm
                'field:id,name'         // Chỉ lấy cột cần thiết từ bảng sân
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            if ($ratings->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Không có đánh giá nào',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $ratings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * Thêm mới một đánh giá.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_sp' => 'nullable|exists:san_pham,id',
                'san_id' => 'nullable|exists:san,id',
                'So_sao' => 'required|numeric|min:1|max:5',
                'Noi_dung' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Kiểm tra ít nhất phải có id_sp hoặc san_id
            if (empty($request->id_sp) && empty($request->san_id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phải cung cấp id sản phẩm hoặc id sân'
                ], 422);
            }

            $userId = Auth::id();

            $rating = Rating::create([
                'id_kh' => $userId,
                'id_sp' => $request->id_sp,
                'san_id' => $request->san_id,
                'So_sao' => $request->So_sao,
                'Noi_dung' => $request->Noi_dung,
                'Trang_thai' => 1 // Mặc định là đã duyệt
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã thêm đánh giá thành công',
                'data' => $rating
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi thêm đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * Hiển thị chi tiết đánh giá.
     */
    public function show(string $id)
    {
        try {
            $rating = Rating::with(['user', 'product', 'field'])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $rating
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * Cập nhật một đánh giá.
     */
    public function update(Request $request, $id)
    {
        try {
            $rating = Rating::findOrFail($id);

            // Kiểm tra quyền sở hữu đánh giá
            if (Auth::id() !== $rating->id_kh) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn không có quyền chỉnh sửa đánh giá này'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'So_sao' => 'required|numeric|min:1|max:5',
                'Noi_dung' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $rating->update([
                'So_sao' => $request->So_sao,
                'Noi_dung' => $request->Noi_dung
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật đánh giá thành công',
                'data' => $rating
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Xóa một đánh giá.
     */
    public function destroy($id)
    {
        try {
            $rating = Rating::findOrFail($id);

            // Kiểm tra quyền sở hữu đánh giá
            if (Auth::id() !== $rating->id_kh && !Auth::user()->isAdmin()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn không có quyền xóa đánh giá này'
                ], 403);
            }

            $rating->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa đánh giá thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi xóa đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái đánh giá (chỉ admin).
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $rating = Rating::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'Trang_thai' => 'required|in:0,1,2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trạng thái không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $rating->update([
                'Trang_thai' => $request->Trang_thai
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật trạng thái đánh giá thành công',
                'data' => $rating
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật trạng thái đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy đánh giá cho sản phẩm.
     */
    public function getProductRatings($productId)
    {
        try {
            $ratings = Rating::where('id_sp', $productId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            $avgRating = $ratings->avg('So_sao');

            return response()->json([
                'status' => 'success',
                'data' => $ratings,
                'average' => round($avgRating, 1)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }
}
