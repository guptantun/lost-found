<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์: {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-gray-800">

    {{-- คำนวณเบอร์โทรล่าสุดที่นี่ --}}
    @php
        $latestItem = $user->items->sortByDesc('created_at')->first();
        $phone = $latestItem ? $latestItem->phone_number : 'ยังไม่มีข้อมูลติดต่อ';
    @endphp

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-indigo-600 font-bold hover:bg-indigo-50 px-3 py-2 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
            </a>
            <div class="font-bold text-gray-700">Thai Lost & Found</div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl p-8 text-center relative overflow-hidden border border-gray-100">
                    
                    @if($user->is_admin)
                        <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-4 py-1 rounded-bl-2xl shadow-sm z-10">
                            <i class="fa-solid fa-crown mr-1"></i> ADMIN
                        </div>
                    @endif

                    <div class="w-32 h-32 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full mx-auto flex items-center justify-center text-white text-5xl font-bold shadow-lg mb-4 ring-4 ring-white">
                        {{ substr($user->name, 0, 1) }}
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $user->name }}</h1>
                    <p class="text-gray-400 text-sm mb-6">สมาชิกตั้งแต่ {{ $user->created_at->format('Y') }}</p>

                    <div class="space-y-4 text-left">
                        <div class="bg-gray-50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-400">อีเมล</p>
                                <p class="text-sm font-medium truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">เบอร์โทร (จากโพสต์ล่าสุด)</p>
                                <p class="text-sm font-medium">{{ $phone }}</p>
                            </div>
                        </div>
                    </div>

                    @if(Auth::check() && Auth::id() == $user->id)
                        <div class="mt-8 space-y-2">
                            @if($user->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block w-full bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-black transition shadow-lg hover:-translate-y-1">
                                <i class="fa-solid fa-gauge-high mr-2"></i> เข้า Admin Center
                            </a>
                            @endif
                            <a href="{{ route('password.change') }}" class="block w-full border border-gray-300 text-gray-600 py-2 rounded-xl font-medium hover:bg-gray-50 transition">
                                <i class="fa-solid fa-key mr-2"></i> เปลี่ยนรหัสผ่าน
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-600"></i> ประวัติการโพสต์
                    </h2>
                    <span class="bg-indigo-100 text-indigo-700 text-sm font-bold px-3 py-1 rounded-full">{{ $user->items->count() }} รายการ</span>
                </div>

                <div class="space-y-4">
                    @forelse($user->items as $item)
                        <a href="{{ route('items.show', $item->id) }}" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-indigo-100 transition group">
                            <div class="flex gap-5">
                                <div class="w-28 h-28 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fa-regular fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-1">
                                            <div>
                                                @if($item->type == 'lost')
                                                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-0.5 rounded border border-red-100">หาย</span>
                                                @else
                                                    <span class="bg-green-50 text-green-600 text-xs font-bold px-2 py-0.5 rounded border border-green-100">เจอ</span>
                                                @endif
                                                <span class="text-gray-400 text-xs ml-2">{{ $item->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        
                                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-indigo-600 transition truncate">{{ $item->title }}</h3>
                                        <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-map-pin text-gray-400"></i> {{ $item->location_text }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex justify-end mt-2">
                                        <span class="text-indigo-600 text-sm font-semibold opacity-0 group-hover:opacity-100 transition transform translate-x-2 group-hover:translate-x-0">
                                            ดูรายละเอียด <i class="fa-solid fa-arrow-right ml-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white p-12 rounded-3xl text-center border-2 border-dashed border-gray-200">
                            <i class="fa-solid fa-ghost text-6xl text-gray-200 mb-4"></i>
                            <h3 class="text-lg font-bold text-gray-500">ยังไม่มีประวัติการโพสต์</h3>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</body>
</html>