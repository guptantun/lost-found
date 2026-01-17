<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->title }} - Thai Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style> 
        body { font-family: 'Prompt', sans-serif; } 
        .leaflet-container { z-index: 0; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition transform hover:scale-105 duration-200">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg">
                        <i class="fa-solid fa-arrow-left text-xl"></i>
                    </div>
                    <span class="text-lg font-bold text-gray-800">กลับหน้าหลัก</span>
                </a>
                
                <div class="flex gap-2 items-center">
                    @auth
                        <a href="{{ route('chat.index') }}" class="relative text-gray-500 hover:text-indigo-600 transition p-2 mr-2">
                            <i class="fa-solid fa-comment-dots text-xl"></i>
                        </a>

                        @if(Auth::id() === $item->user_id)
                            <a href="{{ route('items.edit', $item->id) }}" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> แก้ไข
                            </a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()" class="text-gray-500 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition">
                                    <i class="fa-solid fa-trash mr-1"></i> ลบ
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            <div class="relative h-96 bg-gray-200 group">
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-contain bg-black/5">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100 flex-col">
                        <i class="fa-regular fa-image text-6xl mb-4"></i>
                        <p>ไม่มีรูปภาพ</p>
                    </div>
                @endif
                <div class="absolute top-6 left-6">
                    @if($item->status == 'returned')
                         <span class="px-4 py-2 rounded-full text-sm font-bold bg-gray-800 text-white shadow-lg tracking-wide border-2 border-white">
                            <i class="fa-solid fa-handshake mr-2"></i> คืนของแล้ว
                        </span>
                    @elseif($item->type == 'lost')
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-600 text-white shadow-lg tracking-wide">
                            <i class="fa-solid fa-circle-exclamation mr-2"></i> ประกาศตามหาของ
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-600 text-white shadow-lg tracking-wide">
                            <i class="fa-solid fa-hand-holding-heart mr-2"></i> เจอของหาย
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-md text-sm font-medium">
                                {{ ucfirst($item->category) }}
                            </span>
                            <span class="text-gray-400 text-sm">
                                <i class="fa-regular fa-clock mr-1"></i> {{ $item->created_at->format('d M Y H:i') }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $item->title }}</h1>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 border-l-4 border-indigo-500 pl-3">รายละเอียด</h3>
                            <p class="text-gray-600 leading-relaxed text-lg whitespace-pre-line">{{ $item->description ?? 'ผู้แจ้งไม่ได้ระบุรายละเอียดเพิ่มเติม' }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fa-solid fa-map-location-dot text-indigo-600 mr-2"></i> สถานที่ & เวลา
                            </h3>
                            <ul class="space-y-3 mb-4">
                                <li class="flex items-start">
                                    <span class="w-24 text-gray-500 font-medium">สถานที่:</span>
                                    <span class="text-gray-900 font-medium">{{ $item->location_text }}</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-24 text-gray-500 font-medium">วันที่เกิดเหตุ:</span>
                                    <span class="text-gray-900">{{ $item->event_date->format('d/m/Y') }}</span>
                                </li>
                            </ul>
                            
                            @if($item->latitude && $item->longitude)
                                <div id="map-show" class="w-full h-48 rounded-lg border border-gray-300 z-0"></div>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="block text-center mt-2 text-sm text-indigo-600 hover:underline">
                                    <i class="fa-solid fa-location-arrow"></i> เปิดใน Google Maps
                                </a>
                            @endif
                        </div>

                    </div>
                    <div class="md:col-span-1">
                        @if($item->status == 'returned')
                            <div class="bg-gray-100 border-2 border-gray-200 rounded-xl p-6 text-center">
                                <i class="fa-solid fa-handshake text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-xl font-bold text-gray-600 mb-2">ปิดเคสแล้ว</h3>
                                <p class="text-gray-500 text-sm">รายการนี้ส่งมอบคืนเจ้าของเรียบร้อยแล้ว</p>
                            </div>
                        @else
                            <div class="bg-white border-2 border-indigo-50 rounded-xl p-6 shadow-lg text-center sticky top-24">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-user text-indigo-600 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm mb-1">ผู้ติดต่อ</p>
                                
                                {{-- ======================================================= --}}
                                {{-- 🔥 แก้ไขตรงนี้: ใส่ลิงก์ไปหน้า Profile --}}
                                {{-- ======================================================= --}}
                                <h3 class="text-xl font-bold text-gray-900 mb-6">
                                    <a href="{{ route('profile.show', $item->user_id) }}" class="hover:text-indigo-600 hover:underline transition flex items-center justify-center gap-2">
                                        {{ $item->reporter_name }} <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400"></i>
                                    </a>
                                </h3>
                                {{-- ======================================================= --}}
                                
                                @auth
                                    @if(Auth::id() !== $item->user_id)
                                        <a href="{{ route('chat.start', $item->id) }}" class="block w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition transform hover:-translate-y-1 shadow-lg shadow-indigo-200 mb-3">
                                            <i class="fa-solid fa-comments mr-2"></i> ทักแชทสอบถาม
                                        </a>
                                    @else
                                        <div class="bg-green-50 text-green-700 py-2 rounded-lg mb-3 border border-green-200 text-sm">
                                            <i class="fa-solid fa-check-circle"></i> นี่คือประกาศของคุณ
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="block w-full bg-gray-600 text-white py-3 rounded-lg font-bold hover:bg-gray-700 transition mb-3">
                                        <i class="fa-solid fa-right-to-bracket mr-2"></i> ล็อกอินเพื่อทักแชท
                                    </a>
                                @endauth

                                <a href="tel:{{ $item->phone_number }}" class="block w-full bg-white border-2 border-indigo-600 text-indigo-600 py-3 rounded-lg font-bold hover:bg-indigo-50 transition">
                                    <i class="fa-solid fa-phone mr-2"></i> โทรหา
                                </a>
                                @auth
                                    @if(Auth::id() !== $item->user_id)
                                        <button onclick="openReportModal()" class="block w-full text-gray-400 hover:text-red-500 text-sm font-medium transition mt-4">
                                            <i class="fa-solid fa-flag mr-1"></i> แจ้งประกาศไม่เหมาะสม
                                        </button>
                                        
                                        <form id="reportForm" action="{{ route('reports.store', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="reason" id="reportReason">
                                        </form>
                                    @endif
                                @endauth
                                <p class="text-xs text-gray-400 mt-4"><i class="fa-solid fa-shield-halved mr-1"></i> โปรดระมัดระวังการโอนเงินก่อนได้รับของ</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'เรียบร้อย!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            })
        @endif

        function confirmDelete() {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณแน่ใจหรือไม่ที่จะลบประกาศนี้",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            })
        }

        @if($item->latitude && $item->longitude)
            var lat = {{ $item->latitude }};
            var lng = {{ $item->longitude }};
            
            var map = L.map('map-show', { scrollWheelZoom: false }).setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([lat, lng]).addTo(map)
                .bindPopup("<b>จุดเกิดเหตุ</b><br>{{ $item->location_text }}")
                .openPopup();
        @endif
        
        function openReportModal() {
            Swal.fire({
                title: 'แจ้งปัญหาประกาศ',
                input: 'select',
                inputOptions: {
                    'spam': 'สแปม / โฆษณา',
                    'scam': 'หลอกลวง / มิจฉาชีพ',
                    'fake': 'ข้อมูลเท็จ / ไม่ใช่ของหายจริง',
                    'inappropriate': 'เนื้อหาไม่เหมาะสม'
                },
                inputPlaceholder: 'กรุณาเลือกเหตุผล',
                showCancelButton: true,
                confirmButtonText: 'ส่งรายงาน',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    document.getElementById('reportReason').value = result.value;
                    document.getElementById('reportForm').submit();
                }
            });
        }
    </script>
</body>
</html>