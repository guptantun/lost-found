<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์: {{ $user->name }} - Thai Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-indigo-600 font-bold hover:opacity-80">
                <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
            </a>
            <div class="font-bold text-gray-700">Thai Lost & Found</div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center relative overflow-hidden">
                    
                    @if($user->is_admin)
                        <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-xl shadow-sm z-10">
                            <i class="fa-solid fa-crown"></i> ADMIN
                        </div>
                    @endif

                    <div class="w-32 h-32 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full mx-auto flex items-center justify-center text-white text-5xl font-bold shadow-md mb-4 border-4 border-white ring-2 ring-indigo-100">
                        {{ substr($user->name, 0, 1) }}
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $user->name }}</h1>
                    <p class="text-gray-500 text-sm mb-6">สมาชิกเมื่อ: {{ $user->created_at->format('d/m/Y') }}</p>

                    <div class="text-left space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="truncate">
                                <div class="text-xs text-gray-400">อีเมล</div>
                                <div class="text-sm font-medium">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">เบอร์ติดต่อ (ล่าสุด)</div>
                                <div class="text-sm font-medium">{{ $userPhone }}</div>
                            </div>
                        </div>
                    </div>

                    @if(Auth::check() && Auth::id() == $user->id && $user->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block w-full mt-6 bg-gray-800 text-white py-3 rounded-xl font-bold hover:bg-black transition shadow-lg transform hover:-translate-y-1">
                            <i class="fa-solid fa-gauge-high mr-2"></i> เข้าสู่ Admin Center
                        </a>
                    @endif

                    @if(Auth::check() && Auth::id() == $user->id)
                        <a href="{{ route('password.change') }}" class="block w-full mt-3 bg-white border border-gray-300 text-gray-700 py-2 rounded-xl font-medium hover:bg-gray-50 transition">
                            <i class="fa-solid fa-key mr-2"></i> เปลี่ยนรหัสผ่าน
                        </a>
                    @endif
                </div>
            </div>

            <div class="md:col-span-2">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i> ประวัติการโพสต์ ({{ $user->items->count() }})
                </h2>

                <div class="space-y-4">
                    @forelse($user->items as $item)
                        <a href="{{ route('items.show', $item->id) }}" class="block bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-200 transition group">
                            <div class="flex gap-4">
                                <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fa-regular fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            @if($item->type == 'lost')
                                                <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded mr-2">หาย</span>
                                            @else
                                                <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-0.5 rounded mr-2">เจอ</span>
                                            @endif
                                            <span class="text-gray-400 text-xs">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                        <span class="text-indigo-600 text-sm opacity-0 group-hover:opacity-100 transition">ดูรายละเอียด <i class="fa-solid fa-chevron-right"></i></span>
                                    </div>
                                    
                                    <h3 class="font-bold text-lg text-gray-800 mt-1 mb-1 group-hover:text-indigo-600 transition">{{ $item->title }}</h3>
                                    <p class="text-sm text-gray-500"><i class="fa-solid fa-location-dot mr-1"></i> {{ $item->location_text }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white p-8 rounded-xl text-center border border-dashed border-gray-300">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">ยังไม่มีประวัติการโพสต์</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</body>
</html>