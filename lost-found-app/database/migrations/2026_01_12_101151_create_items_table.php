<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // --- [ส่วนที่เพิ่ม] เชื่อมกับตาราง Users ---
            // เพื่อให้รู้ว่าใครเป็นเจ้าของโพสต์นี้ (nullable ไว้ก่อนเผื่อคนไม่ล็อกอิน)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            // ข้อมูลผู้แจ้ง
            $table->string('reporter_name');
            $table->string('phone_number');
            
            // ข้อมูลสิ่งของ
            $table->string('title')->index();
            
            $table->enum('category', [
                'electronics', 'wallet', 'documents', 'pets', 'clothing', 'others'
            ]);

            $table->text('description')->nullable();
            
            // ประเภท: lost=หาย, found=เจอ
            $table->enum('type', ['lost', 'found'])->index();

            // --- [ส่วนที่เพิ่ม] สถานะของ ---
            // active=ประกาศอยู่, returned=คืนแล้ว, closed=ปิดประกาศ
            $table->enum('status', ['active', 'pending', 'returned', 'closed'])->default('active')->index();
            
            // รูปภาพ
            $table->string('image_path')->nullable();
            
            // สถานที่และเวลา
            $table->string('location_text');
            $table->string('province')->nullable();

            // --- [ส่วนที่เพิ่ม] พิกัดแผนที่ (เผื่อทำ Map View ในอนาคต) ---
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->date('event_date')->index();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};