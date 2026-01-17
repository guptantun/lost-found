<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;

class ProfileController extends Controller
{
    public function show($id)
    {
        // 1. ดึงข้อมูล User คนนั้นมา พร้อมกับรายการของที่เขาเคยโพสต์ (เรียงจากใหม่ไปเก่า)
        $user = User::with(['items' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // 2. แอบดึงเบอร์โทรล่าสุดที่เขาเคยกรอกไว้ในโพสต์ (ถ้ามี) มาโชว์
        $latestContact = $user->items()->latest()->first();
        $userPhone = $latestContact ? $latestContact->phone_number : '-';

        return view('profile.show', compact('user', 'userPhone'));
    }
}