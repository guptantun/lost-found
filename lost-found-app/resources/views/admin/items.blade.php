<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการประกาศ - Admin Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Sarabun', sans-serif; } </style>
</head>
<body class="bg-slate-100 flex h-screen overflow-hidden">

    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="h-16 flex items-center justify-center border-b border-slate-700 bg-slate-950">
            <span class="text-lg font-bold text-yellow-500">ADMIN CENTER</span>
        </div>
        <nav class="flex-1 px-3 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 rounded-xl transition">
                <i class="fa-solid fa-chart-pie w-6"></i> ภาพรวม
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 rounded-xl transition">
                <i class="fa-solid fa-users w-6"></i> จัดการสมาชิก
            </a>
            <a href="{{ route('admin.items') }}" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-xl shadow-lg">
                <i class="fa-solid fa-box-archive w-6"></i> จัดการประกาศ
            </a>
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-800 rounded-xl transition mt-auto">
                <i class="fa-solid fa-house w-6"></i> ไปหน้าเว็บไซต์
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex justify-between items-center px-8">
            <h2 class="text-xl font-bold text-slate-700">จัดการประกาศ (All Items)</h2>
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Admin: {{ Auth::user()->name }}</span>
            </div>
        </header>

        @if(session('success'))
        <div class="px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <strong class="font-bold">สำเร็จ!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="flex-1 overflow-y-auto p-8">
            <div class="bg-white p-4 rounded-xl shadow-sm mb-6">
                <form action="{{ route('admin.items') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาหัวข้อประกาศ..." class="flex-1 border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    
                    <select name="type" class="border border-slate-300 rounded-lg px-4 py-2 outline-none cursor-pointer">
                        <option value="">ทุกประเภท</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>ของหาย</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>เจอของ</option>
                    </select>
                    
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                        <i class="fa-solid fa-filter"></i> กรองข้อมูล
                    </button>
                    <a href="{{ route('admin.items') }}" class="bg-slate-200 text-slate-600 px-4 py-2 rounded-lg hover:bg-slate-300 transition text-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b">
                        <tr>
                            <th class="px-6 py-4">รูปภาพ</th>
                            <th class="px-6 py-4">หัวข้อ</th>
                            <th class="px-6 py-4">ผู้โพสต์</th>
                            <th class="px-6 py-4">ประเภท</th>
                            <th class="px-6 py-4 text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                @if($item->image) <img src="{{ asset('storage/'.$item->image) }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                                @else
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center"><i class="fa-solid fa-image text-slate-400"></i></div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('items.show', $item->id) }}" target="_blank" class="font-bold text-indigo-600 hover:underline block truncate w-48" title="{{ $item->title }}">
                                    {{ $item->title }}
                                </a>
                                <div class="text-xs text-slate-400 mt-1">
                                    <i class="fa-regular fa-clock"></i> {{ $item->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs">
                                        {{ substr($item->user->name ?? 'G', 0, 1) }}
                                    </div>
                                    {{ $item->user->name ?? 'Guest' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->type == 'lost')
                                    <span class="inline-flex items-center gap-1 text-red-600 bg-red-50 px-2.5 py-1 rounded-full text-xs font-bold border border-red-100">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i> ของหาย
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-green-600 bg-green-50 px-2.5 py-1 rounded-full text-xs font-bold border border-green-100">
                                        <i class="fa-solid fa-check-circle text-[10px]"></i> เจอของ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.delete_item', $item->id) }}" method="POST" onsubmit="return confirmDelete(this);">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="ลบประกาศ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-4xl mb-2 block opacity-50"></i>
                                ไม่พบข้อมูลประกาศ
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </main>

    <script>
        function confirmDelete(form) {
            event.preventDefault(); // หยุดการส่งฟอร์มไว้ก่อน
            Swal.fire({
                title: 'ยืนยันลบประกาศ?',
                text: "ประกาศนี้จะหายไปจากระบบทันที!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // ส่งฟอร์มเมื่อกดยืนยัน
                }
            })
            return false;
        }
    </script>
</body>
</html>