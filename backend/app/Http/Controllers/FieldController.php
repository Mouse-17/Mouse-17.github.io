<?php

namespace App\Http\Controllers;

use App\Models\San;
use App\Models\KhungGio;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = San::with(['loai']);
        
        // Apply filters if provided
        if ($request->has('type')) {
            $query->where('ID_Loai', $request->type);
        }
        
        if ($request->has('max_price')) {
            $query->where('Gia', '<=', $request->max_price);
        }
        
        if ($request->has('min_price')) {
            $query->where('Gia', '>=', $request->min_price);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Ten_san', 'like', "%{$search}%")
                  ->orWhere('Dia_chi', 'like', "%{$search}%")
                  ->orWhere('Mo_ta', 'like', "%{$search}%");
            });
        }
        
        $fields = $query->where('Trang_thai', 1)->paginate(12);
        
        return response()->json($fields);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Ten_san' => 'required|string|max:255',
            'Dia_chi' => 'required|string',
            'Gia' => 'required|numeric',
            'Mo_ta' => 'required|string',
            'ID_Loai' => 'required|exists:loai_san,id',
            'So_luong' => 'required|integer|min:1',
            'Hinh_anh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Upload image
        $image = $request->file('Hinh_anh');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/fields'), $imageName);
        
        // Create field
        $field = San::create([
            'Ten_san' => $request->Ten_san,
            'Dia_chi' => $request->Dia_chi,
            'Gia' => $request->Gia,
            'Mo_ta' => $request->Mo_ta,
            'Hinh_anh' => '/uploads/fields/' . $imageName,
            'ID_Loai' => $request->ID_Loai,
            'So_luong' => $request->So_luong,
            'Trang_thai' => 1,
            'view' => 0,
            'diem_danh_gia' => 0,
            'user_id' => Auth::id(),
        ]);
        
        return response()->json([
            'message' => 'Field created successfully',
            'field' => $field
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $field = San::with(['loai', 'owner', 'ratings', 'comments'])->findOrFail($id);
        
        // Increment view count
        $field->increment('view');
        
        return response()->json($field);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $field = San::findOrFail($id);
        
        // Check if user owns this field
        if (Auth::id() != $field->user_id && !Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized to update this field'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'Ten_san' => 'sometimes|string|max:255',
            'Dia_chi' => 'sometimes|string',
            'Gia' => 'sometimes|numeric',
            'Mo_ta' => 'sometimes|string',
            'ID_Loai' => 'sometimes|exists:loai_san,id',
            'So_luong' => 'sometimes|integer|min:1',
            'Trang_thai' => 'sometimes|boolean',
            'Hinh_anh' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Update image if provided
        if ($request->hasFile('Hinh_anh')) {
            $image = $request->file('Hinh_anh');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/fields'), $imageName);
            $field->Hinh_anh = '/uploads/fields/' . $imageName;
        }
        
        // Update other fields
        if ($request->has('Ten_san')) {
            $field->Ten_san = $request->Ten_san;
        }
        
        if ($request->has('Dia_chi')) {
            $field->Dia_chi = $request->Dia_chi;
        }
        
        if ($request->has('Gia')) {
            $field->Gia = $request->Gia;
        }
        
        if ($request->has('Mo_ta')) {
            $field->Mo_ta = $request->Mo_ta;
        }
        
        if ($request->has('ID_Loai')) {
            $field->ID_Loai = $request->ID_Loai;
        }
        
        if ($request->has('So_luong')) {
            $field->So_luong = $request->So_luong;
        }
        
        if ($request->has('Trang_thai')) {
            $field->Trang_thai = $request->Trang_thai ? 1 : 0;
        }
        
        $field->save();
        
        return response()->json([
            'message' => 'Field updated successfully',
            'field' => $field
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $field = San::findOrFail($id);
        
        // Check if user owns this field
        if (Auth::id() != $field->user_id && !Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized to delete this field'
            ], 403);
        }
        
        // Check if field has bookings
        if ($field->bookings()->count() > 0) {
            // Soft delete by changing status instead of removing
            $field->Trang_thai = 0;
            $field->save();
            
            return response()->json([
                'message' => 'Field deactivated successfully'
            ]);
        }
        
        $field->delete();
        
        return response()->json([
            'message' => 'Field deleted successfully'
        ]);
    }
    
    /**
     * Get fields owned by the authenticated user
     */
    public function ownerFields()
    {
        $fields = San::where('user_id', Auth::id())->get();
        
        return response()->json([
            'fields' => $fields
        ]);
    }
    
    /**
     * Get available time slots for a field on a specific date
     */
    public function availableSlots(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $field = San::findOrFail($id);
        $date = $request->date;
        
        // Get all time slots
        $allTimeSlots = KhungGio::where('Trang_thai', 1)->get();
        
        // Get booked slots for the date
        $bookedSlots = Booking::where('id_san', $id)
            ->where('Ngay_dat', $date)
            ->where('Trang_thai', '!=', 0) // Exclude cancelled bookings
            ->pluck('id_kg')
            ->toArray();
        
        $availableSlots = $allTimeSlots->map(function($slot) use ($bookedSlots) {
            $isAvailable = !in_array($slot->id, $bookedSlots);
            return [
                'id' => $slot->id,
                'start_time' => $slot->Gio_bat_dau,
                'end_time' => $slot->Gio_ket_thuc,
                'available' => $isAvailable
            ];
        });
        
        return response()->json([
            'field_id' => $id,
            'date' => $date,
            'available_slots' => $availableSlots
        ]);
    }
}
