<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Report; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ==========================================
    // 1. หน้า Dashboard ภาพรวม
    // ==========================================
    public function index()
    {
        // ดึงข้อมูลสถิติ
        $stats = [
            'total_items'     => Item::count(),
            'lost_items'      => Item::where('type', 'lost')->count(),
            'found_items'     => Item::where('type', 'found')->count(),
            'pending_reports' => Report::where('status', 'pending')->count(), 
        ];

        // ดึงรายการแจ้งปัญหาที่รอตรวจสอบ (ล่าสุด 5 รายการ)
        $reports = Report::with(['user', 'item'])
                         ->where('status', 'pending')
                         ->latest()
                         ->take(5)
                         ->get();

        // ดึงประกาศล่าสุด 5 รายการ
        $latestItems = Item::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'reports', 'latestItems'));
    }

    // ==========================================
    // 2. หน้าจัดการสมาชิก (Users)
    // ==========================================
    public function users(Request $request)
    {
        $query = User::query();

        // ระบบค้นหา (ชื่อ หรือ Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // เรียงลำดับล่าสุด + แบ่งหน้า 15 คน
        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    // ลบสมาชิก (Delete User)
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Security Check: ห้ามลบตัวเอง หรือ ห้ามลบคนที่เป็น Admin
        if ($user->id == Auth::id() || $user->is_admin == 1 || $user->role === 'admin') {
            return back()->with('error', 'ไม่สามารถลบบัญชีผู้ดูแลระบบได้!');
        }
        
        // ลบ User (Database ควรตั้ง cascade ไว้ แต่ถ้าไม่ ลบ manual ก็ได้)
        // $user->items()->delete(); // ถ้าต้องการลบประกาศของคนนี้ด้วยให้เปิดคอมเม้นต์นี้
        
        $user->delete();

        return back()->with('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }

    // ==========================================
    // 3. หน้าจัดการประกาศ (Items)
    // ==========================================
    public function items(Request $request)
    {
        $query = Item::with('user'); // Eager Load user เพื่อลด Query

        // ค้นหา (หัวข้อ, รายละเอียด, สถานที่)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_text', 'like', "%{$search}%");
            });
        }
        
        // กรองประเภท (Lost/Found)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $items = $query->latest()->paginate(20)->withQueryString();

        return view('admin.items', compact('items'));
    }

    // ลบประกาศ (Items)
    public function deleteItem($id)
    {
        $item = Item::findOrFail($id);

        // 1. ลบรูปภาพจาก Storage (สำคัญ: ใช้ชื่อคอลัมน์ image ตามที่เราแก้กันล่าสุด)
        if ($item->image && Storage::exists('public/' . $item->image)) {
            Storage::delete('public/' . $item->image);
        }

        // 2. ลบข้อมูลจากฐานข้อมูล
        $item->delete();

        // 3. ลบ Report ที่เกี่ยวข้องกับโพสต์นี้ (Clean up)
        Report::where('item_id', $id)->delete();

        return back()->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }

    // ==========================================
    // 4. จัดการ Report (แจ้งปัญหา)
    // ==========================================
    
    // ยกฟ้อง (Dismiss Report) - เปลี่ยนสถานะ Report เป็น 'resolved'
    public function dismissReport($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'resolved'; 
        $report->save();

        return back()->with('success', 'ปรับสถานะเป็นตรวจสอบแล้ว (ยกฟ้อง)');
    }
}