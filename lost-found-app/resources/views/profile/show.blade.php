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
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg"><i class="fa-solid fa-arrow-left"></i></div>
                    <span class="font-bold text-gray-700">กลับหน้าหลัก</span>
                </a>
                
                @auth
                    @if(Auth::id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-full shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูล
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <div class="h-48 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 relative">
        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 relative">
                    
                    <div class="pt-8 pb-6 text-center bg-white">
                        <div class="relative inline-block">
                            <div class="w-36 h-36 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-100 mx-auto flex items-center justify-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/'.$user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-5xl font-bold text-gray-300 select-none">{{ substr($user->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="absolute bottom-2 right-2 bg-green-500 text-white w-8 h-8 rounded-full border-4 border-white flex items-center justify-center shadow-sm" title="ยืนยันตัวตนแล้ว">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mt-4">{{ $user->name }}</h1>
                        <p class="text-gray-500 text-sm">สมาชิกเมื่อ {{ $user->created_at->format('M Y') }}</p>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-5">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">ช่องทางติดต่อ</h3>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-indigo-500 shadow-sm"><i class="fa-solid fa-envelope"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-0.5">อีเมล</p>
                                @if(Auth::id() === $user->id)
                                    <p class="text-sm font-medium text-gray-800 break-all">{{ $user->email }}</p>
                                @else
                                    <p class="text-sm text-gray-600">{{ Str::mask($user->email, '*', 3, strpos($user->email, '@') - 3) }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-green-500 shadow-sm"><i class="fa-solid fa-phone"></i></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-0.5">เบอร์โทรศัพท์</p>
                                @if(Auth::id() === $user->id)
                                    <p class="text-sm font-medium text-gray-800">{{ $user->phone ?? '-' }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic flex items-center gap-1">
                                        <i class="fa-solid fa-lock text-xs"></i> ซ่อนเพื่อความปลอดภัย
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($user->line_id)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-green-500 flex items-center justify-center text-white shadow-sm"><i class="fa-brands fa-line text-xl"></i></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-0.5">Line ID</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->line_id }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->facebook)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm"><i class="fa-brands fa-facebook-f text-lg"></i></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-0.5">Facebook</p>
                                <a href="{{ $user->facebook }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline truncate block">
                                    เปิดลิงก์ Facebook <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-1"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if(Auth::id() === $user->id)
                        <div class="px-6 py-3 bg-indigo-50 text-center border-t border-indigo-100">
                            <p class="text-xs text-indigo-700"><i class="fa-regular fa-eye"></i> คุณกำลังดูในมุมมองเจ้าของบัญชี</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-indigo-500 rounded-full block"></span>
                        แนะนำตัว
                    </h2>
                    @if($user->bio)
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line text-lg">{{ $user->bio }}</p>
                    @else
                        <div class="p-6 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                            <p class="text-gray-400">ยังไม่มีข้อมูลแนะนำตัว</p>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-1 h-6 bg-indigo-500 rounded-full block"></span>
                            รายการประกาศ ({{ $user->items->count() }})
                        </h2>
                    </div>

                    @if($user->items->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($user->items as $post)
                            <a href="{{ route('items.show', $post->id) }}" class="group block bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden h-full flex flex-col">
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    @if($post->image_path)
                                        <img src="{{ asset('storage/'.$post->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-regular fa-image text-4xl"></i></div>
                                    @endif
                                    
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md {{ $post->type == 'lost' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                                            {{ $post->type == 'lost' ? 'ตามหา' : 'เจอของ' }}
                                        </span>
                                    </div>
                                    @if($post->status == 'returned')
                                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                            <span class="border-2 border-white text-white px-4 py-1 rounded font-bold uppercase tracking-wide">ปิดเคสแล้ว</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <h3 class="font-bold text-gray-900 text-lg mb-1 line-clamp-1 group-hover:text-indigo-600 transition">{{ $post->title }}</h3>
                                    <p class="text-sm text-gray-500 mb-4 flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1 text-indigo-400"></i> {{ Str::limit($post->location_text, 30) }}
                                    </p>
                                    <div class="mt-auto pt-3 border-t border-gray-50 flex justify-between items-center text-xs text-gray-400">
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                        <span class="group-hover:translate-x-1 transition transform"><i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">ยังไม่มีรายการประกาศ</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</body>
</html>