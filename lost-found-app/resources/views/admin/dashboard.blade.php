<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>National Admin Center - Lost & Found</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Sarabun', sans-serif; }
        .hover-scale { transition: transform 0.2s; }
        .hover-scale:hover { transform: scale(1.02); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex h-screen overflow-hidden">

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

    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-16 bg-white shadow-sm flex justify-between items-center px-8 z-10 animate__animated animate__fadeInDown">
            <h2 class="text-xl font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-gauge-high text-indigo-500"></i> Dashboard Overview
            </h2>
            <div class="flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex items-center gap-2 text-slate-500 hover:text-red-600 transition font-medium text-sm">
                        <i class="fa-solid fa-power-off"></i> Sign Out
                    </button>
                </form>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 bg-slate-50">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate__animated animate__fadeInUp">
                <a href="{{ route('admin.items') }}" class="block bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover-scale group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Items</p>
                            <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $stats['total_items'] }}</h3>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                            <i class="fa-solid fa-layer-group text-xl"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.items', ['type' => 'lost']) }}" class="block bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover-scale group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lost (Active)</p>
                            <h3 class="text-3xl font-extrabold text-red-600 mt-1">{{ $stats['lost_items'] }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                            <i class="fa-solid fa-circle-exclamation text-xl"></i>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.items', ['type' => 'found']) }}" class="block bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover-scale group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Found (Active)</p>
                            <h3 class="text-3xl font-extrabold text-green-600 mt-1">{{ $stats['found_items'] }}</h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg text-green-600 group-hover:bg-green-600 group-hover:text-white transition">
                            <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                        </div>
                    </div>
                </a>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover-scale cursor-pointer group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Reports</p>
                            <h3 class="text-3xl font-extrabold text-orange-500 mt-1">{{ $stats['pending_reports'] }}</h3>
                        </div>
                        <div class="p-3 bg-orange-50 rounded-lg text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition relative">
                            <i class="fa-solid fa-bell text-xl"></i>
                            @if($stats['pending_reports'] > 0)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate__animated animate__fadeInUp animate__delay-1s">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-red-500"></i> รายการแจ้งปัญหา
                            </h3>
                        </div>
                        
                        @if($reports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400">
                                    <tr>
                                        <th class="px-6 py-4">โพสต์ที่ถูกแจ้ง</th>
                                        <th class="px-6 py-4">เหตุผล</th>
                                        <th class="px-6 py-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($reports as $report)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-indigo-600">{{ $report->item->title ?? 'โพสต์ถูกลบแล้ว' }}</div>
                                            <div class="text-xs text-slate-400">ID: {{ $report->item_id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold border border-red-100">
                                                {{ $report->reason }}
                                            </span>
                                            <div class="text-xs text-slate-400 mt-1">โดย: {{ $report->user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button onclick="confirmAction('{{ route('admin.dismiss_report', $report->id) }}', 'ยกเลิก Report', 'โพสต์นี้จะยังคงอยู่ในระบบ')" 
                                                class="px-3 py-1.5 bg-white border border-slate-200 text-slate-500 rounded-lg hover:bg-slate-50 text-xs font-bold transition shadow-sm">
                                                ยกฟ้อง
                                            </button>
                                            
                                            <button onclick="confirmDelete('{{ route('admin.delete_item', $report->item_id) }}')"
                                                class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-bold transition shadow-md hover:shadow-lg">
                                                <i class="fa-solid fa-trash mr-1"></i> ลบโพสต์
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="p-10 text-center">
                            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4 text-green-500 text-2xl animate-bounce">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <p class="text-slate-500">ยอดเยี่ยม! ไม่มีรายการร้องเรียนใหม่</p>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-clock text-indigo-500"></i> ประกาศล่าสุด
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($latestItems as $item)
                                    <tr class="hover:bg-slate-50 transition group">
                                        <td class="px-6 py-4 w-16">
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/'.$item->image_path) }}" class="w-10 h-10 rounded-lg object-cover shadow-sm group-hover:scale-110 transition">
                                            @else
                                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400"><i class="fa-solid fa-image"></i></div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-800">{{ Str::limit($item->title, 30) }}</div>
                                            <div class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->type == 'lost')
                                                <span class="text-red-600 bg-red-50 px-2 py-1 rounded text-xs font-bold">หาย</span>
                                            @else
                                                <span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-bold">เจอ</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="confirmDelete('{{ route('admin.delete_item', $item->id) }}')" class="text-slate-300 hover:text-red-500 transition px-2">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 shadow-xl text-white">
                        <h3 class="font-bold text-lg mb-4">Admin Shortcuts</h3>
                        
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <a href="{{ route('admin.users') }}" class="p-3 bg-slate-700/50 hover:bg-slate-700 rounded-xl transition text-sm flex flex-col items-center justify-center gap-2 border border-slate-600/50 text-center">
                                <i class="fa-solid fa-users text-xl text-blue-400"></i> สมาชิก
                            </a>
                            <a href="{{ route('admin.items') }}" class="p-3 bg-slate-700/50 hover:bg-slate-700 rounded-xl transition text-sm flex flex-col items-center justify-center gap-2 border border-slate-600/50 text-center">
                                <i class="fa-solid fa-box text-xl text-emerald-400"></i> ประกาศ
                            </a>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-700">
                            <p class="text-xs text-slate-400 mb-2 font-bold uppercase">ค้นหาสมาชิกด่วน</p>
                            <form action="{{ route('admin.users') }}" method="GET" class="flex gap-2">
                                <input type="text" name="search" placeholder="ชื่อ / Email..." class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-sm text-white focus:outline-none focus:border-indigo-500 placeholder-slate-600">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-lg transition text-white">
                                    <i class="fa-solid fa-search"></i>
                                </button>
                            </form>
                        </div>

                         <div class="pt-4 mt-4 border-t border-slate-700">
                            <p class="text-xs text-slate-400 mb-2 font-bold uppercase">ค้นหาของหายด่วน</p>
                            <form action="{{ route('admin.items') }}" method="GET" class="flex gap-2">
                                <input type="text" name="search" placeholder="iPhone, กุญแจ..." class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-sm text-white focus:outline-none focus:border-indigo-500 placeholder-slate-600">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-lg transition text-white">
                                    <i class="fa-solid fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <form id="actionForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // 1. Alert เมื่อมีข้อความ Success
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'เรียบร้อย!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // 2. Popup ยืนยันการลบ
        function confirmDelete(url) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "ข้อมูลนี้จะถูกลบถาวรและกู้คืนไม่ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> ลบทิ้งเลย',
                cancelButtonText: 'ยกเลิก',
                background: '#fff',
                customClass: { popup: 'rounded-2xl shadow-xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('actionForm');
                    form.action = url;
                    form.submit();
                }
            })
        }

        // 3. Popup ยืนยันการกระทำทั่วไป (เช่น ยกฟ้อง)
        function confirmAction(url, title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('actionForm');
                    form.action = url;
                    form.submit();
                }
            })
        }
    </script>
</body>
</html>