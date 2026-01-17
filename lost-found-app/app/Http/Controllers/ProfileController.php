<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with(['items' => function($q) {
            $q->latest(); // เรียงจากใหม่ไปเก่า
        }])->findOrFail($id);

        // ดึงเบอร์จากโพสต์ล่าสุดเพื่อแสดงในโปรไฟล์ (ถ้ามี)
        $latestItem = $user->items->first();
        $phone = $latestItem ? $latestItem->phone_number : 'ไม่ได้ระบุ';

        return view('profile.show', compact('user', 'phone'));
    }
}