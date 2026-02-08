<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของ {{ $user->name }} - Thai Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition group">
                    <div class="bg-gray-100 group-hover:bg-indigo-600 group-hover:text-white text-gray-500 p-2 rounded-lg transition"><i class="fa-solid fa-arrow-left"></i></div>
                    <span class="font-bold">กลับหน้าหลัก</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="h-48 bg-gradient-to-r from-slate-700 via-gray-700 to-slate-800 relative">
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-24">
                    
                    <div class="pt-8 pb-6 text-center">
                        <div class="relative inline-block">
                            <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100 mx-auto flex items-center justify-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/'.$user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-5xl font-bold text-gray-300 select-none">{{ substr($user->name, 0, 1) }}</span>
                                @endif
                            </div>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 mt-3">{{ $user->name }}</h1>
                        <p class="text-gray-500 text-xs mt-1">สมาชิกตั้งแต่ {{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 space-y-3">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">ข้อมูลติดต่อ</h3>

                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600"><i class="fa-solid fa-envelope text-xs"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] text-gray-400">อีเมล</p>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        @if($user->phone)
                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-600"><i class="fa-solid fa-phone text-xs"></i></div>
                            <div class="flex-1">
                                <p class="text-[10px] text-gray-400">เบอร์โทรศัพท์</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->phone }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->line_id)
                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600"><i class="fa-brands fa-line text-lg"></i></div>
                            <div class="flex-1">
                                <p class="text-[10px] text-gray-400">Line ID</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->line_id }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->facebook)
                        <a href="{{ $user->facebook }}" target="_blank" class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm hover:bg-blue-50 transition group">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition"><i class="fa-brands fa-facebook-f text-xs"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] text-gray-400">Facebook</p>
                                <p class="text-sm font-medium text-blue-600 truncate">ไปยังโปรไฟล์ <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i></p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 min-h-[500px]">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-list-ul text-indigo-500"></i>
                            รายการประกาศทั้งหมด
                        </h2>
                        <span class="text-xs font-medium bg-gray-100 text-gray-600 px-3 py-1 rounded-full">{{ $user->items->count() }} รายการ</span>
                    </div>

                    @if($user->items->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->items as $post)
                            <a href="{{ route('items.show', $post->id) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 overflow-hidden h-full flex flex-col transition-all">
                                <div class="relative h-40 bg-gray-200 overflow-hidden">
                                    @if($post->image_path)
                                        <img src="{{ asset('storage/'.$post->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                            <i class="fa-regular fa-image text-3xl opacity-30"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-2 left-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold shadow-sm {{ $post->type == 'lost' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                                            {{ $post->type == 'lost' ? 'ตามหา' : 'เจอของ' }}
                                        </span>
                                    </div>
                                    @if($post->status == 'returned')
                                        <div class="absolute inset-0 bg-gray-900/60 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold border border-white px-2 py-1 rounded uppercase tracking-wider">ปิดเคสแล้ว</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3 flex-1 flex flex-col">
                                    <h3 class="font-bold text-gray-800 text-sm mb-1 line-clamp-1 group-hover:text-indigo-600 transition">{{ $post->title }}</h3>
                                    
                                    <div class="flex items-start gap-1 text-xs text-gray-500 mb-2 h-8 overflow-hidden">
                                        <i class="fa-solid fa-location-dot mt-0.5 text-gray-400"></i> 
                                        <span class="line-clamp-2">{{ $post->location_text }}</span>
                                    </div>

                                    <div class="mt-auto pt-2 border-t border-gray-50 flex justify-between items-center text-[10px] text-gray-400">
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-64 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-box-open text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-base font-medium text-gray-900">ยังไม่มีรายการประกาศ</h3>
                            <p class="text-sm text-gray-400">ผู้ใช้นี้ยังไม่ได้โพสต์ประกาศใดๆ</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</body>
</html>