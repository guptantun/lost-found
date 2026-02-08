<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ✅ เพิ่มบรรทัดนี้เพื่อจัดการไฟล์

class ItemController extends Controller
{
    // แสดงรายการทั้งหมด
    public function index(Request $request)
    {
        // ดึงข้อมูล Item พร้อมข้อมูลคนโพสต์ (user) เรียงจากใหม่ไปเก่า
        $query = Item::with('user')->active()->latest();

        // ระบบค้นหา (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location_text', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
            });
        }

        // กรองตามประเภทและหมวดหมู่
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category')) $query->where('category', $request->category);

        // แบ่งหน้า (Pagination)
        $items = $query->paginate(12)->withQueryString();
        
        // สถิติ (Stats)
        $stats = [
            'total' => Item::count(),
            'lost' => Item::where('type', 'lost')->active()->count(),
            'found' => Item::where('type', 'found')->active()->count(),
        ];

        return view('welcome', compact('items', 'stats'));
    }

    // แสดงรายละเอียดทีละชิ้น
    public function show(Item $item)
    {
        return view('show', compact('item'));
    }

    // ฟอร์มสร้างประกาศใหม่ (Store)
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'category' => 'required',
            'location_text' => 'required',
            'event_date' => 'required|date',
            'reporter_name' => 'required',
            'phone_number' => 'required',
            'image' => 'nullable|image|max:10240', // รองรับรูปสูงสุด 10MB
        ]);

        // 2. รับค่าทั้งหมดจากฟอร์ม
        $data = $request->all();
        $data['user_id'] = Auth::id(); // ใส่ ID ของคนโพสต์
        $data['status'] = 'active'; // กำหนดสถานะเริ่มต้น

        // 3. จัดการอัปโหลดรูป (Local Storage) ✅ แก้ไขใหม่
        if ($request->hasFile('image')) {
            // อัปโหลดไปที่โฟลเดอร์ storage/app/public/lost-found-items
            $path = $request->file('image')->store('lost-found-items', 'public');
            
            // สร้าง URL สำหรับเรียกดูรูป (จะได้เป็น /storage/lost-found-items/ชื่อไฟล์.jpg)
            $data['image_path'] = Storage::url($path);
        }

        // 4. บันทึกลงฐานข้อมูล
        Item::create($data);

        return redirect('/')->with('success', 'บันทึกข้อมูลสำเร็จ!');
    }

    // ฟอร์มแก้ไข (Edit)
    public function edit(Item $item)
    {
        // ป้องกันไม่ให้คนอื่นมาแก้ของคนอื่น
        if ($item->user_id !== Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขโพสต์นี้');
        }
        return view('edit', compact('item'));
    }

    // อัปเดตข้อมูล (Update)
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
            'image' => 'nullable|image|max:10240',
            'status' => 'required|in:active,pending,returned,closed', 
        ]);

        $data = $request->all();

        // จัดการอัปโหลดรูปใหม่ (ถ้ามีการเปลี่ยนรูป) ✅ แก้ไขใหม่
        if ($request->hasFile('image')) {
            // ลบรูปเก่าทิ้งก่อน (ถ้ามี) เพื่อไม่ให้รกเครื่อง
            if ($item->image_path) {
                // แปลง URL กลับเป็น Path เพื่อลบไฟล์
                $oldPath = str_replace('/storage/', '', $item->image_path);
                Storage::disk('public')->delete($oldPath);
            }

            // อัปโหลดรูปใหม่
            $path = $request->file('image')->store('lost-found-items', 'public');
            
            // เก็บ URL ใหม่
            $data['image_path'] = Storage::url($path);
        }

        // อัปเดตข้อมูลลงฐานข้อมูล
        $item->update($data);

        return redirect()->route('items.show', $item->id)->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
    }

    // ลบข้อมูล (Destroy)
    public function destroy(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์ลบโพสต์นี้');
        }
        
        // ลบรูปภาพออกจากเครื่องด้วย ✅ เพิ่มส่วนนี้
        if ($item->image_path) {
            $path = str_replace('/storage/', '', $item->image_path);
            Storage::disk('public')->delete($path);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $item->delete();

        return redirect('/')->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }
}