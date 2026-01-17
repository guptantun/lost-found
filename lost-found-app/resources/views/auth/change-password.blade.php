<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปลี่ยนรหัสผ่าน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">🔐 เปลี่ยนรหัสผ่าน</h2>
            <p class="text-gray-500 text-sm">เพื่อความปลอดภัยของบัญชีคุณ</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">รหัสผ่านปัจจุบัน</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">รหัสผ่านใหม่</label>
                <input type="password" name="new_password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="new_password_confirmation" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                ยืนยันการเปลี่ยนรหัส
            </button>
            
            <a href="{{ route('home') }}" class="block text-center mt-4 text-gray-500 text-sm hover:text-gray-800">
                ยกเลิกและกลับหน้าหลัก
            </a>
        </form>
    </div>
</body>
</html>