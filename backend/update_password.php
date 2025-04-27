<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'cuonghotran17022004@gmail.com')->first();

if ($user) {
    $user->password = Hash::make('admin123');
    $user->role = 'admin';
    $user->save();
    echo "Đã cập nhật mật khẩu và vai trò cho tài khoản admin: {$user->email}\n";
} else {
    // Tạo tài khoản mới nếu không tồn tại
    $user = User::create([
        'name' => 'Admin Chủ Sân',
        'email' => 'cuonghotran17022004@gmail.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "Đã tạo tài khoản admin mới: {$user->email}\n";
}

// Tạo thêm tài khoản admin mới để thử
$newAdmin = User::updateOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin Test',
        'password' => Hash::make('123456'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]
);
echo "Tài khoản admin test: admin@example.com / mật khẩu: 123456\n"; 