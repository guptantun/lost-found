<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // เช็คก่อนว่ามีตารางเก่าค้างอยู่ไหม ถ้ามีให้ลบก่อนสร้างใหม่
        Schema::dropIfExists('reports');

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // คนแจ้ง (User)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // โพสต์ที่ถูกแจ้ง (Item)
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            // สาเหตุ
            $table->text('reason');
            // สถานะ (pending = รอตรวจสอบ, resolved = จัดการแล้ว, dismissed = ยกฟ้อง)
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};