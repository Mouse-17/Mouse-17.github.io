<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get all comments with pagination.
     */
    public function index()
    {
        $comments = Comment::with(['user', 'post', 'product', 'field'])->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * Add a new comment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kh' => 'required|exists:users,id',
            'id_sp' => 'nullable|exists:san_ps,id',
            'bai_viet_id' => 'nullable|exists:posts,id',
            'san_id' => 'nullable|exists:sans,id',
            'Noi_dung' => 'required|string|max:1000',
            'Trang_thai' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    /**
     * Display the specified resource.
     * Show a specific comment.
     */
    public function show(string $id)
    {
        $comment = Comment::with(['user', 'post', 'product', 'field'])->find($id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Update a comment.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'Noi_dung' => 'nullable|string|max:1000',
            'Trang_thai' => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Delete a comment.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ]);
    }
}
