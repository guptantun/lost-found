<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, $itemId)
    {
        // ตรวจสอบว่ามีข้อมูลส่งมาไหม
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // บันทึกลงฐานข้อมูล
        Report::create([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
            'reason' => $request->reason, // ใส่เหตุผลลงในฟิลด์ reason ตาม Migration
            'status' => 'pending', // สถานะเริ่มต้นคือ รอตรวจสอบ
        ]);

        // แจ้งเตือนและกลับหน้าเดิม
        return back()->with('success', 'ส่งรายงานเรียบร้อยแล้ว ทางเราจะรีบตรวจสอบครับ');
    }
}