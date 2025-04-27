<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SanP;
use App\Models\San;
use App\Models\Order;
use App\Models\User;
use App\Models\Post;
use App\Models\Contact;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard overview data
     */
    public function index()
    {
        // Only admin can access this
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Count total users by role
        $userStats = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get()
            ->pluck('total', 'role')
            ->toArray();

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get bookings stats
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('Trang_thai', 1)->count();
        $confirmedBookings = Booking::where('Trang_thai', 2)->count();
        $cancelledBookings = Booking::where('Trang_thai', 0)->count();

        // Get recent bookings
        $recentBookings = Booking::with(['customer', 'field'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get orders stats
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('Tong_tien');
        $pendingOrders = Order::where('Trang_thai', 1)->count();

        // Get recent orders
        $recentOrders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get field stats
        $totalFields = San::count();
        $activeFields = San::where('Trang_thai', 1)->count();

        // Get product stats
        $totalProducts = SanP::count();
        $lowStockProducts = SanP::where('So_luong', '<=', 5)->where('So_luong', '>', 0)->count();
        $outOfStockProducts = SanP::where('So_luong', 0)->count();

        // Get categories stats
        $categoriesWithProductCount = DanhMuc::withCount('sanPham')->get();

        // Get brands stats
        $brandsWithProductCount = ThuongHieu::withCount('sanPham')->get();

        // Get content stats
        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $totalRatings = Rating::count();
        $totalContacts = Contact::count();
        $newContacts = Contact::where('status', 'new')->count();

        // Get top 5 bestselling products
        $topSellingProducts = SanP::orderBy('bestseller', 'desc')
            ->take(5)
            ->get(['id', 'Ten_san_pham', 'Gia', 'So_luong', 'bestseller']);

        // Get top 5 most viewed products
        $mostViewedProducts = SanP::orderBy('view', 'desc')
            ->take(5)
            ->get(['id', 'Ten_san_pham', 'Gia', 'So_luong', 'view']);

        return response()->json([
            'user_stats' => $userStats,
            'recent_users' => $recentUsers,
            'booking_stats' => [
                'total' => $totalBookings,
                'pending' => $pendingBookings,
                'confirmed' => $confirmedBookings,
                'cancelled' => $cancelledBookings
            ],
            'recent_bookings' => $recentBookings,
            'order_stats' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'revenue' => $totalRevenue
            ],
            'recent_orders' => $recentOrders,
            'field_stats' => [
                'total' => $totalFields,
                'active' => $activeFields
            ],
            'product_stats' => [
                'total' => $totalProducts,
                'low_stock' => $lowStockProducts,
                'out_of_stock' => $outOfStockProducts
            ],
            'category_stats' => $categoriesWithProductCount,
            'brand_stats' => $brandsWithProductCount,
            'content_stats' => [
                'posts' => $totalPosts,
                'comments' => $totalComments,
                'ratings' => $totalRatings,
                'contacts' => $totalContacts,
                'new_contacts' => $newContacts
            ],
            'top_selling_products' => $topSellingProducts,
            'most_viewed_products' => $mostViewedProducts
        ]);
    }

    /**
     * Get detailed stats for charts
     */
    public function stats(Request $request)
    {
        // Only admin can access this
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $period = $request->get('period', 'week');
        $now = Carbon::now();

        switch ($period) {
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $groupFormat = 'Y-m-d';
                $interval = '1 day';
                break;

            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $groupFormat = 'Y-m';
                $interval = '1 month';
                break;

            case 'week':
            default:
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $groupFormat = 'Y-m-d';
                $interval = '1 day';
                break;
        }

        // Get booking stats over time
        $bookingStats = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as date"), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get revenue stats over time
        $revenueStats = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as date"), DB::raw('sum(Tong_tien) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get user registration stats over time
        $userStats = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$groupFormat}') as date"), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get field usage stats (most booked fields)
        $fieldUsageStats = Booking::select('id_san', DB::raw('count(*) as booking_count'))
            ->with('field')
            ->groupBy('id_san')
            ->orderBy('booking_count', 'desc')
            ->take(5)
            ->get();

        // Get product stats (most ordered products)
        $productStats = DB::table('don_hang_chi_tiet')
            ->select('id_sp', DB::raw('sum(So_luong) as total_quantity'))
            ->groupBy('id_sp')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        // Map product stats to include product name
        $productStats = $productStats->map(function($item) {
            $product = SanP::find($item->id_sp);
            return [
                'id' => $item->id_sp,
                'name' => $product ? $product->Ten_san_pham : 'Unknown',
                'quantity' => $item->total_quantity
            ];
        });

        // Get sales by category stats
        $categoryStats = DB::table('don_hang_chi_tiet')
            ->join('san_pham', 'don_hang_chi_tiet.id_sp', '=', 'san_pham.id')
            ->join('danh_muc', 'san_pham.ID_Danhmuc', '=', 'danh_muc.id')
            ->select('danh_muc.id', 'danh_muc.Ten_danh_muc', DB::raw('sum(don_hang_chi_tiet.So_luong) as total_quantity'))
            ->whereBetween('don_hang_chi_tiet.created_at', [$startDate, $endDate])
            ->groupBy('danh_muc.id', 'danh_muc.Ten_danh_muc')
            ->orderBy('total_quantity', 'desc')
            ->get();

        // Get sales by brand stats
        $brandStats = DB::table('don_hang_chi_tiet')
            ->join('san_pham', 'don_hang_chi_tiet.id_sp', '=', 'san_pham.id')
            ->join('thuong_hieu', 'san_pham.ID_Thuonghieu', '=', 'thuong_hieu.id')
            ->select('thuong_hieu.id', 'thuong_hieu.Ten_thuong_hieu', DB::raw('sum(don_hang_chi_tiet.So_luong) as total_quantity'))
            ->whereBetween('don_hang_chi_tiet.created_at', [$startDate, $endDate])
            ->groupBy('thuong_hieu.id', 'thuong_hieu.Ten_thuong_hieu')
            ->orderBy('total_quantity', 'desc')
            ->get();

        // Get product views over time
        $productViewStats = SanP::select('id', 'Ten_san_pham', 'view')
            ->orderBy('view', 'desc')
            ->take(10)
            ->get();

        // Get customer satisfaction (ratings) stats
        $satisfactionStats = Rating::select(DB::raw('So_sao as rating'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('So_sao')
            ->orderBy('So_sao')
            ->get();

        return response()->json([
            'period' => $period,
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ],
            'booking_stats' => $bookingStats,
            'revenue_stats' => $revenueStats,
            'user_stats' => $userStats,
            'field_usage_stats' => $fieldUsageStats,
            'product_stats' => $productStats,
            'category_stats' => $categoryStats,
            'brand_stats' => $brandStats,
            'product_view_stats' => $productViewStats,
            'satisfaction_stats' => $satisfactionStats
        ]);
    }
}
