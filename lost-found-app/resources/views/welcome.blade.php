<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thai Lost & Found - ศูนย์รวมของหายได้คืน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style> 
        body { font-family: 'Prompt', sans-serif; } 
        .leaflet-container { z-index: 0; }
        
        /* Dropdown Animation */
        .dropdown-menu {
            display: none;
            transform-origin: top right;
        }
        .group:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.2s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="bg-slate-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-90 transition transform hover:scale-105 duration-200">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg shadow-md">
                        <i class="fa-solid fa-magnifying-glass-location text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800 tracking-tight">Thai Lost & Found</span>
                </a>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('chat.index') }}" class="relative text-gray-500 hover:text-indigo-600 transition p-2 mr-1" title="ข้อความของคุณ">
                            <i class="fa-solid fa-comment-dots text-xl"></i>
                        </a>

                        <div class="relative group ml-2 pl-4 border-l border-gray-200">
                            <button class="flex items-center gap-2 text-gray-700 hover:text-indigo-600 transition py-2">
                                <div class="bg-indigo-50 text-indigo-600 rounded-full w-9 h-9 flex items-center justify-center border border-indigo-100 shadow-sm">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <span class="font-medium hidden md:inline">คุณ {{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200 group-hover:rotate-180"></i>
                            </button>

                            <div class="dropdown-menu absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                    <p class="text-xs text-gray-500">เข้าสู่ระบบโดย</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.show', Auth::id()) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-id-card mr-2 text-indigo-400 w-5"></i> โปรไฟล์ของฉัน
                                </a>

                                <a href="{{ route('password.change') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-key mr-2 text-indigo-400 w-5"></i> เปลี่ยนรหัสผ่าน
                                </a>

                                @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                    <i class="fa-solid fa-shield-halved mr-2 text-indigo-400 w-5"></i> จัดการระบบ (Admin)
                                </a>
                                @endif

                                <div class="border-t border-gray-100"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                                        <i class="fa-solid fa-right-from-bracket mr-2 w-5"></i> ออกจากระบบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium text-sm transition">เข้าสู่ระบบ</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-full text-sm font-bold transition shadow-lg hover:shadow-indigo-200 transform hover:-translate-y-0.5">
                            สมัครสมาชิก
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-700 text-white pt-20 pb-24 px-4 mb-12 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight drop-shadow-md mb-4">
                ศูนย์รวมของหาย...ได้คืน 🇹🇭
            </h1>
            <p class="text-lg text-indigo-100 font-light mb-8 max-w-2xl mx-auto">
                พื้นที่กลางสำหรับการแจ้งของหายและส่งคืนเจ้าของ ใช้งานฟรี ปลอดภัย เชื่อถือได้
            </p>
            
            <form action="{{ route('home') }}" method="GET" class="bg-white p-2 rounded-full shadow-2xl max-w-2xl mx-auto flex flex-col md:flex-row gap-2 animate-fade-in-up">
                <div class="flex-grow relative">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" placeholder="ค้นหา... (เช่น กุญแจ, กระเป๋า, iPhone)" value="{{ request('search') }}" 
                        class="w-full pl-12 pr-4 py-3 rounded-full border-none focus:ring-0 text-gray-700 bg-transparent outline-none">
                </div>
                <div class="flex gap-2">
                    <select name="type" class="pl-4 pr-8 py-3 bg-gray-100 rounded-full text-gray-600 text-sm font-medium border-none focus:ring-0 cursor-pointer hover:bg-gray-200 transition">
                        <option value="">ทั้งหมด</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>🔴 ของหาย</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>🟢 เจอของ</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg">
                        ค้นหา
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-indigo-600"></i> รายการประกาศล่าสุด
                </h2>
                <p class="text-sm text-gray-500 mt-1">พบข้อมูลทั้งหมด <span class="font-bold text-indigo-600">{{ $items->count() }}</span> รายการ</p>
            </div>
            
            <form action="{{ route('home') }}" method="GET" class="mt-4 md:mt-0">
                <select name="category" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                    <option value="">-- กรองตามหมวดหมู่ --</option>
                    <option value="wallet" {{ request('category') == 'wallet' ? 'selected' : '' }}>กระเป๋าเงิน</option>
                    <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>มือถือ/IT</option>
                    <option value="documents" {{ request('category') == 'documents' ? 'selected' : '' }}>เอกสาร</option>
                    <option value="pets" {{ request('category') == 'pets' ? 'selected' : '' }}>สัตว์เลี้ยง</option>
                    <option value="others" {{ request('category') == 'others' ? 'selected' : '' }}>อื่นๆ</option>
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($items as $item)
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full hover:-translate-y-1 relative"
                 data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                 
                <a href="{{ route('items.show', $item->id) }}" class="absolute inset-0 z-0"></a>

                <div class="relative h-56 overflow-hidden bg-gray-200">
                    <div class="absolute top-3 left-3 z-10">
                        @if($item->type == 'lost')
                            <span class="bg-red-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">หาย</span>
                        @else
                            <span class="bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">เจอ</span>
                        @endif
                    </div>

                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                            <i class="fa-regular fa-image text-4xl mb-2"></i>
                            <span class="text-xs">ไม่มีรูปภาพ</span>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 pt-8">
                        <p class="text-white text-xs font-light flex items-center">
                            <i class="fa-regular fa-clock mr-1"></i> {{ $item->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-grow relative z-10 bg-white">
                    <div class="mb-1">
                        <span class="text-[10px] uppercase font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded tracking-wide">{{ ucfirst($item->category) }}</span>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 mb-1 line-clamp-1 group-hover:text-indigo-600 transition">
                        {{ $item->title }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4 flex items-center line-clamp-1">
                        <i class="fa-solid fa-location-dot text-gray-400 mr-1.5"></i> 
                        {{ $item->location_text }}
                    </p>
                    
                    <div class="mt-auto pt-3 border-t border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs text-gray-500 font-bold border border-gray-200">
                                {{ substr($item->user ? $item->user->name : $item->reporter_name, 0, 1) }}
                            </div>
                            
                            <div class="text-xs text-gray-500">
                                @if($item->user)
                                    <a href="{{ route('profile.show', $item->user->id) }}" class="font-bold hover:text-indigo-600 hover:underline transition relative z-20">
                                        {{ $item->user->name }}
                                    </a>
                                @else
                                    {{ $item->reporter_name }}
                                @endif
                            </div>
                        </div>
                        
                        <span class="text-indigo-600 text-xs font-bold bg-indigo-50 px-2 py-1 rounded group-hover:bg-indigo-600 group-hover:text-white transition">
                            ดูรายละเอียด
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400 bg-white rounded-3xl border-2 border-dashed border-gray-200" data-aos="zoom-in">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-inbox text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-600">ยังไม่มีรายการประกาศ</h3>
                <p class="text-sm">ลองค้นหาคำอื่น หรือเป็นคนแรกที่ประกาศเลย!</p>
            </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $items->links() }}
        </div>
    </div>

    @auth
    <button onclick="openModal()" class="fixed bottom-8 right-8 bg-indigo-600 text-white w-14 h-14 md:w-16 md:h-16 rounded-full shadow-2xl flex items-center justify-center text-2xl hover:bg-indigo-700 hover:scale-110 transition z-40 group animate-bounce-slow">
        <i class="fa-solid fa-plus group-hover:rotate-90 transition duration-300"></i>
    </button>
    @endauth

    @auth
    <div id="postModal" class="fixed inset-0 bg-black/60 z-[60] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl relative animate-fade-in-up">
            <div class="sticky top-0 bg-white px-6 py-4 border-b flex justify-between items-center z-10 shadow-sm">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-indigo-600"></i> สร้างประกาศใหม่
                </h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-500 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer group">
                        <input type="radio" name="type" value="lost" class="peer sr-only" checked>
                        <div class="border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 rounded-xl p-4 text-center transition group-hover:border-red-200">
                            <div class="w-12 h-12 mx-auto bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-2 peer-checked:bg-red-500 peer-checked:text-white transition">
                                <i class="fa-solid fa-circle-exclamation text-xl"></i>
                            </div>
                            <div class="font-bold text-gray-700 peer-checked:text-red-700">ประกาศของหาย</div>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="type" value="found" class="peer sr-only">
                        <div class="border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 rounded-xl p-4 text-center transition group-hover:border-green-200">
                            <div class="w-12 h-12 mx-auto bg-green-100 text-green-500 rounded-full flex items-center justify-center mb-2 peer-checked:bg-green-500 peer-checked:text-white transition">
                                <i class="fa-solid fa-hand-holding-heart text-xl"></i>
                            </div>
                            <div class="font-bold text-gray-700 peer-checked:text-green-700">ประกาศเจอของ</div>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">หัวข้อประกาศ <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="เช่น ทำกระเป๋าตังค์หาย, เจอ iPhone 14...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 border p-2.5 bg-white">
                            <option value="wallet">กระเป๋าเงิน</option>
                            <option value="electronics">มือถือ / อุปกรณ์ IT</option>
                            <option value="documents">เอกสารราชการ</option>
                            <option value="pets">สัตว์เลี้ยง</option>
                            <option value="clothing">เสื้อผ้า / เครื่องประดับ</option>
                            <option value="others">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">วันที่เกิดเหตุ</label>
                        <input type="date" name="event_date" required class="w-full rounded-lg border-gray-300 border p-2.5">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">สถานที่ <span class="text-red-500">*</span></label>
                    <input type="text" name="location_text" required class="w-full rounded-lg border-gray-300 border p-2.5 mb-2" placeholder="ระบุชื่อสถานที่ (เช่น หน้าเซเว่น สาขา...)">
                    
                    <div class="bg-indigo-50 p-2 rounded-lg border border-indigo-100 mb-2">
                        <p class="text-xs text-indigo-600 flex items-center gap-1"><i class="fa-solid fa-map-pin"></i> ลากหมุดในแผนที่เพื่อระบุพิกัดที่แม่นยำ</p>
                    </div>
                    <div id="map-create" class="w-full h-56 rounded-lg border border-gray-300 z-0 shadow-inner"></div>
                    
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">รายละเอียดเพิ่มเติม</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 border p-2.5" placeholder="สี, ยี่ห้อ, จุดสังเกต..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">รูปภาพประกอบ</label>
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <p class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2"><i class="fa-solid fa-address-card text-gray-400"></i> ข้อมูลติดต่อกลับ</p>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="reporter_name" value="{{ Auth::user()->name }}" required class="w-full rounded-lg border-gray-300 border p-2.5" placeholder="ชื่อผู้ติดต่อ">
                        <input type="text" name="phone_number" required class="w-full rounded-lg border-gray-300 border p-2.5" placeholder="เบอร์โทรศัพท์">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3.5 rounded-xl font-bold hover:bg-indigo-700 shadow-lg hover:shadow-indigo-200 transition transform hover:-translate-y-0.5">
                        บันทึกประกาศ
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        AOS.init({ duration: 800, once: true });

        // -- Modal & Map Logic --
        var map, marker;

        function openModal() {
            const modal = document.getElementById('postModal');
            modal.classList.remove('hidden');
            
            // รอให้ Modal แสดงผลก่อนค่อยโหลดแผนที่ (แก้ปัญหากล่องเทา)
            setTimeout(function() {
                if (!map) {
                    initMap();
                } else {
                    map.invalidateSize();
                }
            }, 200);
        }

        function closeModal() {
            document.getElementById('postModal').classList.add('hidden');
        }

        function initMap() {
            var defaultLat = 13.7563;
            var defaultLng = 100.5018;

            map = L.map('map-create').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            // เมื่อลากหมุด
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                document.getElementById('lat').value = position.lat;
                document.getElementById('lng').value = position.lng;
            });

            // เมื่อคลิกแผนที่
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('lat').value = e.latlng.lat;
                document.getElementById('lng').value = e.latlng.lng;
            });

            // Set ค่าเริ่มต้น
            document.getElementById('lat').value = defaultLat;
            document.getElementById('lng').value = defaultLng;

            // ขอ GPS User
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                });
            }
        }

        // -- Alerts --
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#4F46E5'
            })
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#EF4444'
            })
        @endif
    </script>
</body>
</html>