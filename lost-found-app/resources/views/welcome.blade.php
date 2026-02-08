<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thai Lost & Found - ศูนย์รวมของหายได้คืน</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <script src="//unpkg.com/alpinejs" defer></script>

    <style> 
        body { font-family: 'Prompt', sans-serif; } 
        
        /* แก้ไข z-index ของแผนที่ */
        .leaflet-container { z-index: 1 !important; }
        
        /* Custom Scrollbar for Modal */
        .modal-scroll::-webkit-scrollbar { width: 8px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 4px; }
        .modal-scroll::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-90 transition transform hover:scale-105 duration-200">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg">
                        <i class="fa-solid fa-magnifying-glass-location text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800 tracking-tight">Thai Lost & Found</span>
                </a>
                
                <div class="flex items-center gap-4">
                    @auth
                        <div x-data="{ open: false }" class="relative ml-2 pl-4 border-l border-gray-200">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-gray-700 hover:text-indigo-600 transition py-2 outline-none">
                                <div class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <span class="font-medium">คุณ {{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50" 
                                 style="display: none;">
                                
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                    <p class="text-sm text-gray-500">เข้าสู่ระบบโดย</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                                </div>

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
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-md">
                            สมัครสมาชิก
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white py-16 px-4 mb-10" data-aos="fade-down">
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight drop-shadow-lg">
                ศูนย์กลางแจ้งของหาย และส่งคืนเจ้าของ
            </h1>
            <p class="text-lg md:text-xl text-indigo-100 font-light">
                สังคมแห่งการแบ่งปัน ช่วยกันสอดส่อง ดูแล และส่งคืนสิ่งของสำคัญ
            </p>
            
            @auth
                <button onclick="openModal()" 
                    class="mt-6 bg-white text-indigo-600 px-8 py-3 rounded-full font-bold text-lg hover:bg-indigo-50 transition transform hover:scale-105 shadow-xl">
                    <i class="fa-solid fa-plus-circle mr-2"></i> แจ้งของหาย / เจอของ
                </button>
            @else
                <a href="{{ route('login') }}" 
                    class="mt-6 inline-block bg-white text-indigo-600 px-8 py-3 rounded-full font-bold text-lg hover:bg-indigo-50 transition transform hover:scale-105 shadow-xl">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> เข้าสู่ระบบเพื่อโพสต์
                </a>
            @endauth
            
            <div class="grid grid-cols-3 gap-4 max-w-lg mx-auto mt-8 pt-8 border-t border-indigo-400/30">
                <div><div class="text-2xl font-bold">{{ $items->total() }}</div><div class="text-xs opacity-75">ประกาศทั้งหมด</div></div>
                <div><div class="text-2xl font-bold">{{ $stats['lost'] ?? 0 }}</div><div class="text-xs opacity-75">ของหาย</div></div>
                <div><div class="text-2xl font-bold">{{ $stats['found'] ?? 0 }}</div><div class="text-xs opacity-75">เจอของ</div></div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <div class="bg-white p-4 rounded-xl shadow-md mb-8 -mt-24 relative z-10 border border-gray-100" data-aos="fade-up">
            <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="ค้นหา เช่น 'กระเป๋าตังค์ สยาม'..." value="{{ request('search') }}" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                
                <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-gray-600">
                    <option value="">ทุกประเภทประกาศ</option>
                    <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>ของหาย (Lost)</option>
                    <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>เจอของ (Found)</option>
                </select>

                <select name="category" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-gray-600">
                    <option value="">ทุกหมวดหมู่</option>
                    <option value="wallet" {{ request('category') == 'wallet' ? 'selected' : '' }}>กระเป๋าเงิน</option>
                    <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>มือถือ/IT</option>
                    <option value="documents" {{ request('category') == 'documents' ? 'selected' : '' }}>เอกสาร</option>
                    <option value="pets" {{ request('category') == 'pets' ? 'selected' : '' }}>สัตว์เลี้ยง</option>
                    <option value="clothing" {{ request('category') == 'clothing' ? 'selected' : '' }}>เสื้อผ้า</option>
                    <option value="others" {{ request('category') == 'others' ? 'selected' : '' }}>อื่นๆ</option>
                </select>

                <button type="submit" class="bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter"></i> กรองข้อมูล
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($items as $item)
            <a href="{{ route('items.show', $item->id) }}" class="block group">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100 h-full flex flex-col relative"
                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    
                    <div class="absolute top-3 left-3 z-10">
                        @if($item->type == 'lost')
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md shadow-sm">หาย</span>
                        @else
                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-md shadow-sm">เจอ</span>
                        @endif
                    </div>

                    <div class="h-48 bg-gray-200 overflow-hidden relative">
                        @if($item->image_path)
                            <img src="{{ $item->image_path }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                                 alt="{{ $item->title }}"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                <i class="fa-regular fa-image text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ ucfirst($item->category) }}</span>
                            <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1 line-clamp-1 group-hover:text-indigo-600 transition">{{ $item->title }}</h3>
                        <p class="text-sm text-gray-500 mb-3 flex items-center">
                            <i class="fa-solid fa-location-dot text-gray-400 mr-1"></i> 
                            <span class="truncate">{{ $item->location_text }}</span>
                        </p>
                        
                        <div class="mt-auto pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500">โดย: 
                                <span class="font-bold text-gray-700">
                                    {{ $item->user ? $item->user->name : $item->reporter_name }}
                                </span>
                            </span>
                            <span class="text-indigo-600 text-sm font-semibold group-hover:underline">ดูข้อมูล <i class="fa-solid fa-arrow-right text-xs"></i></span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400" data-aos="zoom-in">
                <i class="fa-solid fa-inbox text-6xl mb-4 text-gray-200"></i>
                <p class="text-lg">ยังไม่มีรายการประกาศในขณะนี้</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8 px-4">
            {{ $items->links() }}
        </div>
    </div>

    @auth
    <div id="postModal" class="fixed inset-0 bg-black/60 z-[60] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto modal-scroll shadow-2xl relative animate-fade-in-up">
            
            <div class="sticky top-0 bg-white px-6 py-4 border-b flex justify-between items-center z-20 shadow-sm">
                <h3 class="text-xl font-bold text-gray-800"><i class="fa-solid fa-pen-to-square text-indigo-600 mr-2"></i>สร้างประกาศใหม่</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition bg-gray-100 hover:bg-red-50 rounded-full w-8 h-8 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="lost" class="peer sr-only" checked>
                        <div class="border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 rounded-xl p-4 text-center transition hover:border-red-200 h-full flex flex-col justify-center items-center">
                            <i class="fa-solid fa-circle-exclamation text-3xl mb-2 text-red-500"></i>
                            <div class="font-bold text-gray-700">ประกาศของหาย</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="found" class="peer sr-only">
                        <div class="border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 rounded-xl p-4 text-center transition hover:border-green-200 h-full flex flex-col justify-center items-center">
                            <i class="fa-solid fa-hand-holding-heart text-3xl mb-2 text-green-500"></i>
                            <div class="font-bold text-gray-700">ประกาศเจอของ</div>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อประกาศ <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="ระบุสิ่งที่หายให้ชัดเจน">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 border p-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="wallet">กระเป๋าเงิน</option>
                            <option value="electronics">มือถือ / อุปกรณ์ IT</option>
                            <option value="documents">เอกสารราชการ</option>
                            <option value="pets">สัตว์เลี้ยง</option>
                            <option value="clothing">เสื้อผ้า / เครื่องประดับ</option>
                            <option value="others">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เกิดเหตุ</label>
                        <input type="date" name="event_date" required class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ <span class="text-red-500">*</span></label>
                    <input type="text" name="location_text" required class="w-full rounded-lg border-gray-300 border p-2 mb-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="ระบุชื่อสถานที่ (เช่น หน้าเซเว่น สาขา...)">
                    
                    <p class="text-xs text-indigo-600 mb-2 bg-indigo-50 inline-block px-2 py-1 rounded"><i class="fa-solid fa-map-pin"></i> ลากหมุดเพื่อระบุพิกัดที่แม่นยำ</p>
                    <div id="map-create" class="w-full h-64 rounded-lg border border-gray-300 z-0 shadow-inner"></div>
                    
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเพิ่มเติม</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="สี, ตำหนิ, ลักษณะเด่น..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพประกอบ</label>
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-200 rounded-full">
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <p class="text-sm font-bold text-gray-700 mb-3"><i class="fa-solid fa-address-card mr-1"></i> ข้อมูลติดต่อกลับ</p>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="reporter_name" value="{{ Auth::user()->name }}" required class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="ชื่อผู้ติดต่อ">
                        <input type="text" name="phone_number" required class="w-full rounded-lg border-gray-300 border p-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="เบอร์โทรศัพท์">
                    </div>
                </div>

                <div class="pt-2 sticky bottom-0 bg-white pb-2 z-10">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
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
        var map = null;
        var marker = null;

        function openModal() {
            const modal = document.getElementById('postModal');
            if(modal) {
                modal.classList.remove('hidden');
                
                // รอให้ Modal แสดงผลก่อนค่อยโหลดแมพ
                setTimeout(function() {
                    initMap();
                    if(map) map.invalidateSize(); 
                }, 300);
            }
        }

        function closeModal() {
            const modal = document.getElementById('postModal');
            if(modal) modal.classList.add('hidden');
        }

        function initMap() {
            var defaultLat = 13.7563;
            var defaultLng = 100.5018;

            // ตรวจสอบว่ามีแมพอยู่แล้วหรือไม่
            if(map !== null) return;
            
            var mapContainer = document.getElementById('map-create');
            if(!mapContainer) return; // ป้องกัน error ถ้าไม่มี element

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

            // เมื่อคลิกที่แผนที่
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('lat').value = e.latlng.lat;
                document.getElementById('lng').value = e.latlng.lng;
            });

            // ตั้งค่าเริ่มต้น
            document.getElementById('lat').value = defaultLat;
            document.getElementById('lng').value = defaultLng;

            // ขอพิกัดปัจจุบัน
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

        // ปิด Modal เมื่อคลิกพื้นที่ว่างข้างนอก
        window.onclick = function(event) {
            const modal = document.getElementById('postModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // -- SweetAlert2 Alerts --
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#4f46e5'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#4f46e5'
            });
        @endif
    </script>
</body>
</html>