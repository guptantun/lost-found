<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Thai Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        
        <a href="{{ route('home') }}" class="block text-center mb-8 hover:opacity-80 transition transform hover:scale-105">
            <div class="inline-block bg-indigo-600 text-white p-3 rounded-xl mb-2 shadow-md">
                <i class="fa-solid fa-magnifying-glass-location text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">เข้าสู่ระบบ</h1>
            <p class="text-gray-500 text-sm">Thai Lost & Found</p>
        </a>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                <input type="email" name="email" class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                <input type="password" name="password" class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>
            
            <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">เข้าสู่ระบบ</button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">สมัครสมาชิก</a>
        </div>
        <div class="mt-2 text-center text-sm">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600">กลับหน้าหลัก</a>
        </div>
    </div>

    <script>
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'เข้าสู่ระบบไม่สำเร็จ',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'ลองใหม่',
                confirmButtonColor: '#d33'
            })
        @endif
    </script>
</body>
</html>