<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อความทั้งหมด - Thai Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-indigo-600 font-bold text-lg hover:opacity-80">
                <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
            </a>
            <h1 class="font-bold text-xl text-gray-800">กล่องข้อความ</h1>
            <div class="w-10"></div> </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px]">
            @forelse($conversations as $chat)
                <a href="{{ route('chat.show', $chat->id) }}" class="block p-4 border-b border-gray-100 hover:bg-indigo-50 transition flex items-center gap-4 group">
                    {{-- Avatar ตัวอักษรย่อ --}}
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-indigo-600 font-bold text-xl border border-gray-200 group-hover:bg-white group-hover:border-indigo-300 transition">
                        {{ substr($chat->otherUser()->name, 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-bold text-gray-900 truncate">{{ $chat->otherUser()->name }}</h3>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $chat->updated_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2 py-0.5 rounded-full font-medium truncate max-w-[150px]">
                                <i class="fa-solid fa-box mr-1"></i> {{ $chat->item->title ?? 'สินค้าถูกลบ' }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-500 truncate group-hover:text-indigo-600 transition">
                            {{ $chat->messages->last()->body ?? 'เริ่มการสนทนา...' }}
                        </p>
                    </div>
                    
                    <div class="text-gray-300 group-hover:text-indigo-400">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center h-full py-20 text-gray-400">
                    <i class="fa-regular fa-comments text-6xl mb-4 text-gray-200"></i>
                    <p class="text-lg font-medium">ไม่มีข้อความ</p>
                    <p class="text-sm">ทักหาเจ้าของประกาศเพื่อเริ่มคุยได้เลย</p>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>