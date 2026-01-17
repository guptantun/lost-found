<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

class ProfileController extends Controller
{
    // แสดงหน้าเปลี่ยนรหัสผ่าน
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // บันทึกการเปลี่ยนรหัสผ่าน (แก้ตรงนี้ให้เหลือ min:4)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:4|confirmed', // <-- แก้เป็น 4 ตัวตรงนี้
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }

        User::where('id', Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!');
    }

    // แสดงหน้าโปรไฟล์ (เพิ่มฟังก์ชันนี้เพื่อให้กดดูโปรไฟล์ได้)
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // ดึงรายการที่คนนี้เคยโพสต์
        $items = Item::where('user_id', $id)->orderBy('created_at', 'desc')->get();

        return view('profile.show', compact('user', 'items'));
    }
}