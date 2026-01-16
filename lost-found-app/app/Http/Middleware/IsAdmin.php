<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // ถ้าล็อกอินแล้ว และเป็น Admin (is_admin = 1) ถึงจะให้ผ่าน
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // ถ้าไม่ใช่ ให้ดีดกลับหน้าแรก หรือแจ้ง Error 403
        abort(403, 'คุณไม่มีสิทธิ์เข้าถึงส่วนผู้ดูแลระบบ');
    }
}