<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::create([
    'name' => 'Chủ Sân Test',
    'email' => 'chusantest@keysport.com',
    'password' => Hash::make('chusan123'),
    'role' => 'field_owner',
    'phone' => '0987654321'
]);

if ($user) {
    echo "Tạo tài khoản chủ sân thành công!\n";
    echo "Email: chusantest@keysport.com\n";
    echo "Mật khẩu: chusan123\n";
} else {
    echo "Tạo tài khoản không thành công!\n";
} 