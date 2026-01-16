<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>แชทกับ {{ $conversation->otherUser()->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { font-family: 'Prompt', sans-serif; } 
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar { -ms-overflow-style: none;  scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 h-[100dvh] flex flex-col">

    <div class="bg-white shadow-sm px-4 py-3 flex items-center justify-between sticky top-0 z-10 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('chat.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-600 transition">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="font-bold text-gray-800 text-lg leading-tight">{{ $conversation->otherUser()->name }}</h2>
                <a href="{{ route('items.show', $conversation->item_id) }}" class="text-xs text-indigo-600 hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-tag"></i> {{ Str::limit($conversation->item->title ?? 'สินค้าถูกลบ', 30) }}
                </a>
            </div>
        </div>
    </div>

    <div id="message-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#f0f2f5]">
        <div class="text-center text-xs text-gray-400 my-4">
            <span>เริ่มการสนทนา {{ $conversation->created_at->format('d/m/Y') }}</span>
        </div>

        @foreach($conversation->messages as $message)
            @if($message->user_id == Auth::id())
                {{-- ข้อความของเรา (ขวา) --}}
                <div class="flex justify-end mb-2">
                    <div class="max-w-[75%] relative group">
                        <div class="bg-indigo-600 text-white px-4 py-2 rounded-2xl rounded-tr-none shadow-sm text-sm md:text-base">
                            {{ $message->body }}
                        </div>
                        <span class="text-[10px] text-gray-400 block text-right mt-1 mr-1">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @else
                {{-- ข้อความคนอื่น (ซ้าย) --}}
                <div class="flex justify-start mb-2 items-end gap-2">
                    {{-- Avatar เล็กๆ --}}
                    <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-[10px] text-gray-600 font-bold">
                        {{ substr($message->user->name, 0, 1) }}
                    </div>
                    <div class="max-w-[75%]">
                        <div class="bg-white text-gray-800 px-4 py-2 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-sm md:text-base">
                            {{ $message->body }}
                        </div>
                        <span class="text-[10px] text-gray-400 block ml-1 mt-1">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="bg-white p-3 md:p-4 border-t border-gray-200">
        <form action="{{ route('chat.send', $conversation->id) }}" method="POST" class="flex gap-2 items-center max-w-4xl mx-auto">
            @csrf
            <input type="text" name="body" required autocomplete="off"
                class="flex-1 bg-gray-100 border-0 rounded-full px-5 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-sm md:text-base" 
                placeholder="พิมพ์ข้อความ...">
            
            <button type="submit" class="bg-indigo-600 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-indigo-700 active:scale-95 transition shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-paper-plane text-lg"></i>
            </button>
        </form>
    </div>

    <script>
        // Auto Scroll to bottom
        window.onload = function() {
            const container = document.getElementById('message-container');
            container.scrollTop = container.scrollHeight;
        }
    </script>
</body>
</html>