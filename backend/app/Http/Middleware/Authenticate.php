<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Nếu là request API (expectsJson), trả về null để middleware xử lý trả về lỗi 401
        // thay vì cố gắng chuyển hướng đến route 'login' không tồn tại
        if ($request->expectsJson()) {
            return null;
        }
        
        // Với request web thông thường, chuyển hướng đến trang đăng nhập frontend
        return '/login';
    }
}
