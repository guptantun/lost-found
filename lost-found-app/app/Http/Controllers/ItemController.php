<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // ✅ เพิ่มบรรทัดนี้

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('user')->active()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location_text', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
            });
        }
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category')) $query->where('category', $request->category);

        $items = $query->paginate(12)->withQueryString();
        
        $stats = [
            'total' => Item::count(),
            'lost' => Item::where('type', 'lost')->active()->count(),
            'found' => Item::where('type', 'found')->active()->count(),
        ];

        return view('welcome', compact('items', 'stats'));
    }

    public function show(Item $item)
    {
        return view('show', compact('item'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'category' => 'required',
            'location_text' => 'required',
            'event_date' => 'required|date',
            'reporter_name' => 'required',
            'phone_number' => 'required',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->all();
        
        $data['user_id'] = Auth::id(); 

        if ($request->hasFile('image')) {
            // ✅ แก้ไข: อัปโหลดขึ้น Cloudinary และขอ URL กลับมา
            $uploadedFileUrl = $request->file('image')->storeOnCloudinary('items')->getSecurePath();
            $data['image_path'] = $uploadedFileUrl;
        }

        Item::create($data);
        return redirect('/')->with('success', 'บันทึกข้อมูลสำเร็จ!');
    }

    public function edit(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขโพสต์นี้');
        }
        return view('edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขโพสต์นี้');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'category' => 'required',
            'location_text' => 'required',
            'event_date' => 'required|date',
            'reporter_name' => 'required',
            'phone_number' => 'required',
            'image' => 'nullable|image|max:5120',
            'status' => 'required|in:active,pending,returned,closed', 
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // ✅ แก้ไข: อัปโหลดรูปใหม่ขึ้น Cloudinary
            // (เราไม่ลบรูปเก่าใน Cloudinary เพื่อความง่าย และ URL เก่าจะถูกแทนที่ใน Database เอง)
            $uploadedFileUrl = $request->file('image')->storeOnCloudinary('items')->getSecurePath();
            $data['image_path'] = $uploadedFileUrl;
        }

        $item->update($data);

        return redirect()->route('items.show', $item->id)->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
    }

    public function destroy(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์ลบโพสต์นี้');
        }

        // ✅ เอาโค้ดลบไฟล์ local ออก (เพราะไฟล์อยู่บน Cloudinary แล้ว และเราเก็บเป็น URL)
        // ถ้าต้องการลบรูปบน Cloudinary ด้วย ต้องเก็บ public_id แต่เพื่อให้ง่าย เราปล่อยไว้ก่อน
        
        $item->delete();
        return redirect('/')->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }
}