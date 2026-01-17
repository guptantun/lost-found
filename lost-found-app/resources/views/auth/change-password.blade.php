@extends('layout') {{-- หรือชื่อไฟล์ layout หลักของคุณ --}}

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-key-fill"></i> เปลี่ยนรหัสผ่าน</h5>
                </div>
                <div class="card-body">
                    
                    {{-- แจ้งเตือนสำเร็จ --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        {{-- 1. รหัสผ่านปัจจุบัน --}}
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่านปัจจุบัน</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        {{-- 2. รหัสผ่านใหม่ --}}
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">ต้องมีความยาวอย่างน้อย 8 ตัวอักษร</small>
                        </div>

                        {{-- 3. ยืนยันรหัสผ่านใหม่ --}}
                        <div class="mb-3">
                            <label class="form-label">ยืนยันรหัสผ่านใหม่ อีกครั้ง</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            บันทึกรหัสผ่านใหม่
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection