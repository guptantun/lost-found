<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        Report::create([
            'item_id' => $itemId,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'ขอบคุณที่แจ้งปัญหา เราจะตรวจสอบโดยเร็วที่สุด');
    }
}