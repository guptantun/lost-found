<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขประกาศ - {{ $item->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                <h1 class="text-white text-xl font-bold"><i class="fa-solid fa-pen-to-square mr-2"></i> แก้ไขข้อมูลประกาศ</h1>
                <a href="{{ route('items.show', $item->id) }}" class="text-indigo-100 hover:text-white transition"><i class="fa-solid fa-times text-xl"></i></a>
            </div>

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">สถานะประกาศ</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 border p-2 bg-gray-50 focus:ring-indigo-500">
                        <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>🟢 กำลังประกาศ (Active)</option>
                        <option value="returned" {{ $item->status == 'returned' ? 'selected' : '' }}>🤝 ส่งคืนเจ้าของแล้ว (Returned)</option>
                        <option value="closed" {{ $item->status == 'closed' ? 'selected' : '' }}>🔴 ปิดประกาศ (Closed)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท</label>
                        <select name="type" class="w-full rounded-lg border-gray-300 border p-2">
                            <option value="lost" {{ $item->type == 'lost' ? 'selected' : '' }}>ของหาย</option>
                            <option value="found" {{ $item->type == 'found' ? 'selected' : '' }}>เจอของ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 border p-2">
                            <option value="wallet" {{ $item->category == 'wallet' ? 'selected' : '' }}>กระเป๋าเงิน</option>
                            <option value="electronics" {{ $item->category == 'electronics' ? 'selected' : '' }}>มือถือ/IT</option>
                            <option value="documents" {{ $item->category == 'documents' ? 'selected' : '' }}>เอกสาร</option>
                            <option value="pets" {{ $item->category == 'pets' ? 'selected' : '' }}>สัตว์เลี้ยง</option>
                            <option value="clothing" {{ $item->category == 'clothing' ? 'selected' : '' }}>เสื้อผ้า</option>
                            <option value="others" {{ $item->category == 'others' ? 'selected' : '' }}>อื่นๆ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อ</label>
                    <input type="text" name="title" value="{{ $item->title }}" required class="w-full rounded-lg border p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" rows="4" class="w-full rounded-lg border p-2">{{ $item->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ระบุ</label>
                    <input type="text" name="location_text" value="{{ $item->location_text }}" required class="w-full rounded-lg border p-2 mb-2">
                    <div id="map-edit" class="w-full h-64 rounded-lg border border-gray-300"></div>
                    <input type="hidden" name="latitude" id="lat" value="{{ $item->latitude }}">
                    <input type="hidden" name="longitude" id="lng" value="{{ $item->longitude }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ (อัปโหลดใหม่เพื่อเปลี่ยน)</label>
                    @if($item->image_path)
                        <div class="mb-2">
                            <img src="{{ $item->image_path }}" class="h-32 rounded-lg border shadow-sm">
                        </div>
                    @endif
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700">
                </div>
                
                <hr>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ติดต่อ</label>
                        <input type="text" name="reporter_name" value="{{ $item->reporter_name }}" required class="w-full rounded-lg border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทร</label>
                        <input type="text" name="phone_number" value="{{ $item->phone_number }}" required class="w-full rounded-lg border p-2">
                    </div>
                </div>
                
                <input type="hidden" name="event_date" value="{{ $item->event_date }}">

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('items.show', $item->id) }}" class="px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">ยกเลิก</a>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var lat = {{ $item->latitude ?? 13.7563 }};
        var lng = {{ $item->longitude ?? 100.5018 }};
        
        var map = L.map('map-edit').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        
        var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            document.getElementById('lat').value = position.lat;
            document.getElementById('lng').value = position.lng;
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });
    </script>
</body>
</html>