<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูล - {{ $item->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="max-w-2xl mx-auto py-10 px-4">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">แก้ไขข้อมูลประกาศ</h2>
                <a href="{{ route('items.show', $item->id) }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-xl"></i> ยกเลิก
                </a>
            </div>
            
            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                    <label class="block text-sm font-bold text-indigo-900 mb-2">สถานะปัจจุบัน</label>
                    <select name="status" class="w-full rounded-lg border-indigo-300 border shadow-sm p-2 focus:ring-indigo-500">
                        <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>🟢 กำลังตามหา / ประกาศอยู่ (Active)</option>
                        <option value="returned" {{ $item->status == 'returned' ? 'selected' : '' }}>🤝 คืนของเรียบร้อยแล้ว (Returned)</option>
                        <option value="closed" {{ $item->status == 'closed' ? 'selected' : '' }}>🔴 ปิดประกาศ (Closed)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 bg-gray-100 p-1 rounded-lg">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="type" value="lost" class="peer sr-only" {{ $item->type == 'lost' ? 'checked' : '' }}>
                        <div class="py-2 text-sm font-medium rounded-md text-gray-500 peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm transition">แจ้งของหาย</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="type" value="found" class="peer sr-only" {{ $item->type == 'found' ? 'checked' : '' }}>
                        <div class="py-2 text-sm font-medium rounded-md text-gray-500 peer-checked:bg-white peer-checked:text-green-600 peer-checked:shadow-sm transition">แจ้งเจอของ</div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หัวข้อประกาศ</label>
                    <input type="text" name="title" value="{{ $item->title }}" class="w-full rounded-lg border-gray-300 border shadow-sm p-2" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 border shadow-sm p-2 bg-white">
                            @foreach(['wallet'=>'กระเป๋าเงิน', 'electronics'=>'มือถือ/IT', 'documents'=>'เอกสาร', 'pets'=>'สัตว์เลี้ยง', 'clothing'=>'เสื้อผ้า', 'others'=>'อื่นๆ'] as $key => $label)
                                <option value="{{ $key }}" {{ $item->category == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัด</label>
                        <input type="text" name="province" value="{{ $item->province }}" class="w-full rounded-lg border-gray-300 border shadow-sm p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่</label>
                    <input type="text" name="location_text" value="{{ $item->location_text }}" class="w-full rounded-lg border-gray-300 border shadow-sm p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เกิดเหตุ</label>
                    <input type="date" name="event_date" value="{{ $item->event_date->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 border shadow-sm p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 border shadow-sm p-2">{{ $item->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">เปลี่ยนรูปภาพ (ถ้ามี)</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="border-t pt-4 grid grid-cols-2 gap-3">
                    <input type="text" name="reporter_name" value="{{ $item->reporter_name }}" class="w-full rounded-lg border-gray-300 border shadow-sm p-2" placeholder="ชื่อผู้ติดต่อ">
                    <input type="text" name="phone_number" value="{{ $item->phone_number }}" class="w-full rounded-lg border-gray-300 border shadow-sm p-2" placeholder="เบอร์โทร">
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 shadow-lg transition">
                    บันทึกการแก้ไข
                </button>
            </form>
        </div>
    </div>
</body>
</html>