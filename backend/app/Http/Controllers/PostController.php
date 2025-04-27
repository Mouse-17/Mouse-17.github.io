<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Model Post
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // Lấy danh sách bài viết
    public function index()
    {
        $posts = Post::with(['category', 'author'])->get(); // Lấy tất cả bài viết cùng danh mục và tác giả
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    // Thêm bài viết mới
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'Tieu_de' => 'required|string|max:255',
            'Noi_dung' => 'required|string',
            'ID_Loai' => 'required|exists:loai_bai_viet,id',
            'thumbnail' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'slug' => 'required|string|unique:bai_viet,slug',
            'status' => 'required|in:published,draft,pending,archived',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        // Tạo bài viết mới
        $post = Post::create($request->all());

        return response()->json(['status' => 'success', 'data' => $post], 201);
    }

    // Cập nhật bài viết
    public function update(Request $request, $id)
    {
        // Tìm bài viết
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'Bài viết không tồn tại'], 404);
        }

        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'Tieu_de' => 'sometimes|required|string|max:255',
            'Noi_dung' => 'sometimes|required|string',
            'ID_Loai' => 'sometimes|required|exists:loai_bai_viet,id',
            'thumbnail' => 'nullable|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'slug' => 'sometimes|required|string|unique:bai_viet,slug,' . $id,
            'status' => 'sometimes|required|in:published,draft,pending,archived',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        // Cập nhật bài viết
        $post->update($request->all());

        return response()->json(['status' => 'success', 'data' => $post]);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        // Tìm bài viết
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['status' => 'error', 'message' => 'Bài viết không tồn tại'], 404);
        }

        // Xóa bài viết
        $post->delete();

        return response()->json(['status' => 'success', 'message' => 'Bài viết đã được xóa']);
    }

    // Thêm danh mục bài viết
    public function storeCategory(Request $request)
    {
        // Logic thêm danh mục bài viết
    }

    // Cập nhật danh mục bài viết
    public function updateCategory(Request $request, $id)
    {
        // Logic cập nhật danh mục bài viết
    }

    // Xóa danh mục bài viết
    public function destroyCategory($id)
    {
        // Logic xóa danh mục bài viết
    }
}
