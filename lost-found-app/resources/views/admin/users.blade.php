<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสมาชิก - Admin Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Sarabun', sans-serif; } </style>
</head>
<body class="bg-slate-100 flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-2xl z-20 hidden md:flex animate__animated animate__slideInLeft">
        <div class="h-16 flex items-center justify-center border-b border-slate-700 bg-slate-950">
            <div class="flex items-center gap-2 text-yellow-500">
                <i class="fa-solid fa-shield-cat text-2xl"></i>
                <span class="text-lg font-bold tracking-wide text-white">ADMIN CENTER</span>
            </div>
        </div>

        <nav class="flex-1 px-3 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl text-white shadow-lg transform transition hover:scale-105">
                <i class="fa-solid fa-chart-pie w-6 text-center"></i> ภาพรวมระบบ
            </a>
            
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition">
                <i class="fa-solid fa-globe w-6 text-center"></i> หน้าเว็บไซต์หลัก
            </a>
            
            <div class="pt-6 pb-2">
                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Management</p>
            </div>
            
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition">
                <i class="fa-solid fa-users-gear w-6 text-center"></i> จัดการสมาชิก
            </a>
            
            <a href="{{ route('admin.items') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition">
                <i class="fa-solid fa-box-archive w-6 text-center"></i> ฐานข้อมูลของหาย
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-900">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random" class="w-10 h-10 rounded-full border-2 border-slate-600">
                <div>
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-green-400"><i class="fa-solid fa-circle text-[8px] mr-1"></i>Online</p>
                </div>
            </div>
        </div>
    </aside>
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex justify-between items-center px-8">
            <h2 class="text-xl font-bold text-slate-700">จัดการสมาชิก (User Management)</h2>
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Admin: {{ Auth::user()->name }}</span>
            </div>
        </header>

        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
        @endif
        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด!',
                text: "{{ session('error') }}",
            });
        </script>
        @endif

        <div class="flex-1 overflow-y-auto p-8">
            <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex justify-between items-center">
                <form action="{{ route('admin.users') }}" method="GET" class="flex gap-2 w-1/2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ หรือ Email..." class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition"><i class="fa-solid fa-search"></i></button>
                    <a href="{{ route('admin.users') }}" class="bg-slate-200 text-slate-600 px-4 py-2 rounded-lg hover:bg-slate-300 flex items-center justify-center transition"><i class="fa-solid fa-rotate-left"></i></a>
                </form>
                <div class="text-slate-500 text-sm">ทั้งหมด <span class="font-bold text-indigo-600">{{ $users->total() }}</span> คน</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">ชื่อผู้ใช้</th>
                            <th class="px-6 py-4">อีเมล</th>
                            <th class="px-6 py-4 text-center">บทบาท</th>
                            <th class="px-6 py-4 text-right">จัดการสิทธิ์ / ลบ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-xs text-slate-400">#{{ $user->id }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($user->usertype === 'admin' || $user->is_admin == 1)
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs px-2.5 py-1 rounded-full border border-yellow-200 font-bold shadow-sm">
                                        <i class="fa-solid fa-crown text-[10px]"></i> ADMIN
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-bold border border-slate-200">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            @if($user->usertype === 'admin' || $user->is_admin == 1)
                                                <input type="hidden" name="usertype" value="user">
                                                <button type="submit" onclick="return confirm('ยืนยัน: ปลด {{ $user->name }} ออกจากการเป็น Admin?')"
                                                    class="text-xs bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition font-medium flex items-center gap-1">
                                                    <i class="fa-solid fa-arrow-down"></i> ปลด
                                                </button>
                                            @else
                                                <input type="hidden" name="usertype" value="admin">
                                                <button type="submit" onclick="return confirm('ยืนยัน: ตั้ง {{ $user->name }} เป็น Admin?')"
                                                    class="text-xs bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition font-bold flex items-center gap-1 shadow-sm">
                                                    <i class="fa-solid fa-crown"></i> เลื่อนขั้น
                                                </button>
                                            @endif
                                        </form>

                                        <form action="{{ route('admin.delete_user', $user->id) }}" method="POST" onsubmit="return confirmDelete(this);">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-white hover:bg-red-500 font-bold text-xs bg-white border border-red-200 px-3 py-1.5 rounded-lg transition duration-200 flex items-center gap-1">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-xs italic py-1.5 px-2">บัญชีของคุณ</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </main>

    <script>
        function confirmDelete(form) {
            event.preventDefault();
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "ข้อมูลของผู้ใช้นี้และประกาศทั้งหมดจะหายไปถาวร!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'ใช่, ลบทิ้งเลย',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
            return false;
        }
    </script>
</body>
</html>