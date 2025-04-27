<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


use App\Models\SanP;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;
use App\Models\SanPhamMauSize;
use App\Models\Mau;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function showListProduct(Request $request)
    {
        try {
            // Lấy trang hiện tại từ request, mặc định là 1
            $page = $request->input('trang', 1);

            // Khởi tạo query builder
            $query = SanP::join('danh_muc', 'san_pham.ID_Danhmuc', '=', 'danh_muc.id')
            ->join('thuong_hieu', 'san_pham.ID_Thuonghieu', '=', 'thuong_hieu.id')
            ->leftJoin('san_pham_mau_size', 'san_pham.id', '=', 'san_pham_mau_size.ID_SP')
            ->leftJoin('danh_gia', 'san_pham.id', '=', 'danh_gia.ID_SP')
            ->where('san_pham.So_luong', '>', 0)
            ->select(
                'san_pham.*',
                'thuong_hieu.Ten_thuong_hieu as Thuong_hieu',
                'danh_muc.Ten_danh_muc as Danh_muc',
                DB::raw('COALESCE(AVG(danh_gia.So_sao), 0) as diem_trung_binh'),
                DB::raw('COUNT(danh_gia.id) as tong_danh_gia')
            )
            ->groupBy('san_pham.id', 'thuong_hieu.Ten_thuong_hieu', 'danh_muc.Ten_danh_muc');


            if (!empty($request->input('tukhoa'))) {
                $search = $request->input('tukhoa');
                $query->where(function($q) use ($search) {
                    $q->where('san_pham.Ten_san_pham', 'like', "%{$search}%")
                    ->orWhere('san_pham.Mo_ta', 'like', "%{$search}%")
                    ->orWhere('san_pham.Gia', 'like', "%{$search}%")
                    ->orWhere('thuong_hieu.Ten_thuong_hieu', 'like', "%{$search}%")
                    ->orWhere('danh_muc.Ten_danh_muc', 'like', "%{$search}%");
                });
            }


            // Phân trang
            $products = $query->paginate(9, ['*'], 'trang', $page);

            return response()->json([
                'status' => 'success',
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all products for admin
     */
    public function index()
    {
        try {
            $products = SanP::with(['danhMuc', 'thuongHieu'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product categories
     */
    public function categories()
    {
        try {
            $categories = DanhMuc::all();

            return response()->json([
                'status' => 'success',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách danh mục: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all brands
     */
    public function brands()
    {
        try {
            $brands = ThuongHieu::all();

            return response()->json([
                'status' => 'success',
                'data' => $brands
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách thương hiệu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCategory(){
        try {
            $categories = DanhMuc::all();

            return response()->json([
                'status' => 'success',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách danh mục: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new product category
     */
    public function storeCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Ten_danh_muc' => 'required|string|max:255|unique:danh_muc',
                'Mo_ta' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $category = DanhMuc::create([
                'Ten_danh_muc' => $request->Ten_danh_muc,
                'Mo_ta' => $request->Mo_ta ?? ''
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã tạo danh mục thành công',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo danh mục: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product category
     */
    public function updateCategory(Request $request, $id)
    {
        try {
            $category = DanhMuc::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'Ten_danh_muc' => 'required|string|max:255|unique:danh_muc,Ten_danh_muc,' . $id,
                'Mo_ta' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $category->update([
                'Ten_danh_muc' => $request->Ten_danh_muc,
                'Mo_ta' => $request->Mo_ta ?? $category->Mo_ta
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật danh mục thành công',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật danh mục: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product category
     */
    public function destroyCategory($id)
    {
        try {
            $category = DanhMuc::findOrFail($id);

            // Check if there are products using this category
            $productsCount = SanP::where('ID_Danhmuc', $id)->count();

            if ($productsCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể xóa danh mục này vì có ' . $productsCount . ' sản phẩm đang sử dụng'
                ], 400);
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa danh mục thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi xóa danh mục: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Ten_san_pham' => 'required|string|max:255',
                'Gia' => 'required|numeric|min:0',
                'Mo_ta' => 'required|string',
                'ID_Danhmuc' => 'required|exists:danh_muc,id',
                'ID_Thuonghieu' => 'required|exists:thuong_hieu,id',
                'So_luong' => 'required|integer|min:0',
                'Hinh_anh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'mau_size' => 'required|array|min:1',
                'mau_size.*.mau_id' => 'required|exists:mau,id',
                'mau_size.*.size_id' => 'required|exists:size,id',
                'mau_size.*.so_luong' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Upload image
            $imagePath = $request->file('Hinh_anh')->store('products', 'public');

            // Create product
            $product = SanP::create([
                'Ten_san_pham' => $request->Ten_san_pham,
                'Gia' => $request->Gia,
                'Mo_ta' => $request->Mo_ta,
                'ID_Danhmuc' => $request->ID_Danhmuc,
                'ID_Thuonghieu' => $request->ID_Thuonghieu,
                'So_luong' => $request->So_luong,
                'Hinh_anh' => '/storage/' . $imagePath,
                'bestseller' => $request->bestseller ?? 0,
                'view' => 0
            ]);

            // Create color-size combinations
            foreach ($request->mau_size as $mauSize) {
                SanPhamMauSize::create([
                    'ID_SP' => $product->id,
                    'ID_Mau' => $mauSize['mau_id'],
                    'ID_Size' => $mauSize['size_id'],
                    'So_luong' => $mauSize['so_luong']
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Đã tạo sản phẩm thành công',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific product
     */
    public function show($id)
    {
        try {
            $product = SanP::with([
                'danhMuc',
                'thuongHieu',
                'san_pham_mau_size',
                'san_pham_mau_size.mau',
                'san_pham_mau_size.size'
            ])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product
     */
    public function update(Request $request, $id)
    {
        try {
            $product = SanP::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'Ten_san_pham' => 'sometimes|required|string|max:255',
                'Gia' => 'sometimes|required|numeric|min:0',
                'Mo_ta' => 'sometimes|required|string',
                'ID_Danhmuc' => 'sometimes|required|exists:danh_muc,id',
                'ID_Thuonghieu' => 'sometimes|required|exists:thuong_hieu,id',
                'So_luong' => 'sometimes|required|integer|min:0',
                'Hinh_anh' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'mau_size' => 'sometimes|array',
                'mau_size.*.id' => 'sometimes|exists:san_pham_mau_size,id',
                'mau_size.*.mau_id' => 'sometimes|required|exists:mau,id',
                'mau_size.*.size_id' => 'sometimes|required|exists:size,id',
                'mau_size.*.so_luong' => 'sometimes|required|integer|min:0',
                'bestseller' => 'sometimes|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Upload new image if provided
            if ($request->hasFile('Hinh_anh')) {
                // Delete old image
                $oldImagePath = str_replace('/storage/', '', $product->Hinh_anh);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }

                // Upload new image
                $imagePath = $request->file('Hinh_anh')->store('products', 'public');
                $product->Hinh_anh = '/storage/' . $imagePath;
            }

            // Update product fields
            if ($request->has('Ten_san_pham')) {
                $product->Ten_san_pham = $request->Ten_san_pham;
            }

            if ($request->has('Gia')) {
                $product->Gia = $request->Gia;
            }

            if ($request->has('Mo_ta')) {
                $product->Mo_ta = $request->Mo_ta;
            }

            if ($request->has('ID_Danhmuc')) {
                $product->ID_Danhmuc = $request->ID_Danhmuc;
            }

            if ($request->has('ID_Thuonghieu')) {
                $product->ID_Thuonghieu = $request->ID_Thuonghieu;
            }

            if ($request->has('So_luong')) {
                $product->So_luong = $request->So_luong;
            }

            if ($request->has('bestseller')) {
                $product->bestseller = $request->bestseller;
            }

            $product->save();

            // Update color-size combinations if provided
            if ($request->has('mau_size')) {
                foreach ($request->mau_size as $mauSize) {
                    if (isset($mauSize['id'])) {
                        // Update existing combination
                        $existingMauSize = SanPhamMauSize::findOrFail($mauSize['id']);
                        $existingMauSize->update([
                            'ID_Mau' => $mauSize['mau_id'] ?? $existingMauSize->ID_Mau,
                            'ID_Size' => $mauSize['size_id'] ?? $existingMauSize->ID_Size,
                            'So_luong' => $mauSize['so_luong'] ?? $existingMauSize->So_luong
                        ]);
                    } else {
                        // Create new combination
                        SanPhamMauSize::create([
                            'ID_SP' => $product->id,
                            'ID_Mau' => $mauSize['mau_id'],
                            'ID_Size' => $mauSize['size_id'],
                            'So_luong' => $mauSize['so_luong']
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật sản phẩm thành công',
                'data' => $product->load(['danhMuc', 'thuongHieu', 'san_pham_mau_size', 'san_pham_mau_size.mau', 'san_pham_mau_size.size'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        try {
            $product = SanP::findOrFail($id);

            // Delete associated color-size combinations
            SanPhamMauSize::where('ID_SP', $id)->delete();

            // Delete product image
            $imagePath = str_replace('/storage/', '', $product->Hinh_anh);
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Delete product
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa sản phẩm thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi xóa sản phẩm: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sortProduct(Request $request)
    {
        try {
            $popular = $request->input('popular');
            $price = $request->input('price');
            $minPrice = $request->input('minPrice');
            $maxPrice = $request->input('maxPrice');
            $category = $request->input('category');
            $brand = $request->input('brand');
            $rating = $request->input('rating');

            // In thông tin debug để kiểm tra tham số đầu vào
            \Log::info('Filter parameters:', [
                'popular' => $popular,
                'price' => $price,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
                'category' => $category,
                'brand' => $brand,
                'rating' => $rating
            ]);

            // Truy vấn chính
            $query = SanP::select(
                    'san_pham.*',
                    'thuong_hieu.Ten_thuong_hieu as Thuong_hieu',
                    'danh_muc.Ten_danh_muc as Danh_muc'
                )
                ->leftJoin('danh_muc', 'san_pham.ID_Danhmuc', '=', 'danh_muc.id')
                ->leftJoin('thuong_hieu', 'san_pham.ID_Thuonghieu', '=', 'thuong_hieu.id');

            // Lọc theo khoảng giá
            if (!empty($minPrice)) {
                $minPrice = (float) $minPrice;
                $query->where('san_pham.Gia', '>=', $minPrice);
            }

            if (!empty($maxPrice)) {
                $maxPrice = (float) $maxPrice;
                $query->where('san_pham.Gia', '<=', $maxPrice);
            }

            // Lọc theo danh mục
            if (!empty($category)) {
                $query->where('san_pham.ID_Danhmuc', $category);
            }

            // Lọc theo thương hiệu
            if (!empty($brand)) {
                $query->where('san_pham.ID_Thuonghieu', $brand);
            }

            // Sắp xếp theo giá - ƯU TIÊN NHẤT
            if (!empty($price)) {
                if ($price === 'Thấp - Cao') {
                    $query->orderBy('san_pham.Gia', 'asc');
                    \Log::info('Sắp xếp giá từ thấp đến cao');
                } elseif ($price === 'Cao - Thấp') {
                    $query->orderBy('san_pham.Gia', 'desc');
                    \Log::info('Sắp xếp giá từ cao đến thấp');
                }
            }
            // Sắp xếp theo phổ biến (chỉ áp dụng nếu không có sắp xếp theo giá)
            elseif (!empty($popular)) {
                switch ($popular) {
                    case 'Mới nhất':
                        $query->orderBy('san_pham.created_at', 'desc');
                        break;
                    case 'Cũ nhất':
                        $query->orderBy('san_pham.created_at', 'asc');
                        break;
                    case 'Phổ biến':
                        $query->orderBy('san_pham.view', 'desc');
                        break;
                }
            }

            // Thực hiện truy vấn và kết xuất log cho debug
            \Log::info('SQL Query: ' . $query->toSql());
            \Log::info('SQL Bindings: ', $query->getBindings());

            // Truy vấn sản phẩm
            $products = $query->paginate(9);

            // Nếu không có kết quả, log để debug
            if ($products->isEmpty()) {
                \Log::warning('Không tìm thấy sản phẩm nào phù hợp với bộ lọc');
            }

            // Tính rating cho mỗi sản phẩm và lọc theo rating nếu cần
            $filteredProducts = collect();

            foreach ($products as $product) {
                // Tính rating từ bảng danh_gia
                $danhGiaRating = \DB::table('danh_gia')
                    ->where('ID_SP', $product->id)
                    ->avg('So_sao');

                // Tính rating từ bảng ratings
                $ratingsRating = \DB::table('ratings')
                    ->where('ID_SP', $product->id)
                    ->avg('So_sao');

                // Tính điểm trung bình tổng hợp
                if ($danhGiaRating && $ratingsRating) {
                    $avgRating = ($danhGiaRating + $ratingsRating) / 2;
                } elseif ($danhGiaRating) {
                    $avgRating = $danhGiaRating;
                } elseif ($ratingsRating) {
                    $avgRating = $ratingsRating;
                } else {
                    $avgRating = 0;
                }

                // Gán giá trị rating cho sản phẩm
                $product->diem_trung_binh = $avgRating;

                // Lọc theo rating nếu có
                if (!empty($rating)) {
                    $ratingFilter = (float) $rating;
                    $minRating = $ratingFilter;
                    $maxRating = $ratingFilter + 1;

                    // Nếu đã chọn 5 sao, thì maxRating = 5
                    if ($ratingFilter == 5) {
                        $maxRating = 5.01; // Để bao gồm cả 5.0
                    }

                    if ($avgRating >= $minRating && $avgRating < $maxRating) {
                        $filteredProducts->push($product);
                    }
                } else {
                    $filteredProducts->push($product);
                }
            }

            // Nếu đang lọc theo rating, cập nhật lại thông tin phân trang
            if (!empty($rating)) {
                $currentPage = $products->currentPage();
                $perPage = $products->perPage();
                $total = $filteredProducts->count();
                $lastPage = ceil($total / $perPage);

                $paginationInfo = [
                    'current_page' => $currentPage,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $total
                ];

                return response()->json([
                    'status' => 'success',
                    'data' => $filteredProducts->slice(($currentPage - 1) * $perPage, $perPage)->values(),
                    'pagination' => $paginationInfo
                ]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lọc sản phẩm: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString() // Thêm chi tiết lỗi để debug
            ], 500);
        }
    }


    public function showdetail($id)
    {
        try {
            $productdetail = SanP::with([
                'danhMuc',
                'thuongHieu',
                'san_pham_mau_size',
                'san_pham_mau_size.mau',
                'san_pham_mau_size.size'
            ])->find($id);

            if (!$productdetail) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm không tồn tại'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $productdetail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function bestseller(){
        try {
            $bestseller = SanP::where('bestseller', '>', 0)->orderBy('bestseller', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'data' => $bestseller
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update bestseller value for a product
     */
    public function updateBestseller(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bestseller' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validação falhou',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = SanP::find($id);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm không tồn tại'
                ], 404);
            }

            $product->bestseller = $request->bestseller ? 1 : 0;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái bestseller thành công',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật trạng thái bestseller: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top products for admin dashboard
     */
    public function getTopProducts()
    {
        try {
            // Get top 10 products by view count
            $products = SanP::select(
                    'san_pham.*',
                    'danh_muc.Ten_danh_muc as Ten_LSP'
                )
                ->leftJoin('danh_muc', 'san_pham.ID_Danhmuc', '=', 'danh_muc.id')
                ->orderBy('san_pham.view', 'desc')
                ->limit(10)
                ->get();

            // Add rating and sales information
            $products = $products->map(function($product) {
                // Get average rating
                $rating = DB::table('danh_gia')
                        ->where('ID_SP', $product->id)
                        ->avg('So_sao') ?? 0;

                // Get sales count from don_hang_chi_tiet table
                $soldCount = DB::table('don_hang_chi_tiet')
                        ->where('ID_SP', $product->id)
                        ->sum('So_luong') ?? 0;

                $product->So_sao = round($rating, 1);
                $product->sold_count = (int)$soldCount;

                return $product;
            });

            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getTopProducts: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi lấy danh sách sản phẩm bán chạy: ' . $e->getMessage()
            ], 500);
        }
    }
}
