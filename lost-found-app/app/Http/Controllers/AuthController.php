<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // แสดงหน้า Login
    public function showLogin() {
        return view('auth.login');
    }

    // แสดงหน้า Register
    public function showRegister() {
        return view('auth.register');
    }

    // ระบบสมัครสมาชิก
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user); // สมัครเสร็จล็อกอินให้เลย

        return redirect('/')->with('success', 'ยินดีต้อนรับ! สมัครสมาชิกเรียบร้อยแล้ว');
    }

    // ระบบเข้าสู่ระบบ
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        // --- เพิ่มส่วนเช็คสถานะตรงนี้ ---
        if (Auth::user()->status == 'banned') {
            Auth::logout(); // เตะออกทันที
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'บัญชีของคุณถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ',
            ]);
        }
        // -----------------------------

        $request->session()->regenerate();
        
        // ถ้าเป็น Admin ให้ไปหน้า Admin Dashboard เลย
        if (Auth::user()->is_admin) {
             return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
    ]);
}

    // ระบบออกจากระบบ
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    // 1. แสดงหน้าฟอร์มเปลี่ยนรหัส
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // 2. บันทึกรหัสผ่านใหม่
    public function changePassword(\Illuminate\Http\Request $request)
    {
        // ตรวจสอบความถูกต้อง
        $request->validate([
            'current_password' => 'required', // ต้องกรอกรหัสเดิม
            'new_password' => 'required|min:8|confirmed', // รหัสใหม่ต้อง 8 ตัวขึ้นไป + ต้องตรงกับช่องยืนยัน
        ]);

        // เช็คว่า "รหัสผ่านเดิม" ที่กรอกมา ถูกต้องไหม?
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, auth()->user()->password)) {
            // ถ้าไม่ถูก ให้เด้งกลับไปฟ้อง error
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้องครับ']);
        }

        // ถ้าทุกอย่างผ่าน -> บันทึกรหัสใหม่
        $user = auth()->user();
        /** @var \App\Models\User $user */
        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!');
    }
}