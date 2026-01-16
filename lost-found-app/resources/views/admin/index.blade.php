<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Prompt', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <nav class="bg-gray-900 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold"><i class="fa-solid fa-user-shield mr-2"></i> Admin Panel</h1>
            <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition">กลับหน้าบ้าน</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">รายการแจ้งปัญหา (Reports)</h2>

        @if($reports->count() > 0)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันเวลา</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">หัวข้อประกาศ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ผู้แจ้ง</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เหตุผล</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('items.show', $report->item_id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    {{ $report->item->title }} <i class="fa-solid fa-external-link-alt text-xs ml-1"></i>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $report->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $report->reason }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <form action="{{ route('admin.dismiss_report', $report->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันลบรายงานนี้ (ไม่ลบโพสต์)?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-gray-600">
                                        ยกฟ้อง
                                    </button>
                                </form>

                                <form action="{{ route('admin.delete_item', $report->item_id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันลบโพสต์นี้ถาวร?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md">
                                        ลบโพสต์
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-lg shadow">
                <i class="fa-solid fa-check-circle text-6xl text-green-400 mb-4"></i>
                <p class="text-gray-500 text-lg">ยังไม่มีการแจ้งปัญหาเข้ามาครับ</p>
            </div>
        @endif
    </div>
</body>
</html>