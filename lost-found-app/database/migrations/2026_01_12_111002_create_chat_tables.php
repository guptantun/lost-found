<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ตารางห้องสนทนา
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->timestamps();
            
            // ป้องกันการสร้างห้องซ้ำสำหรับ Item เดิมและคู่สนทนาเดิม
            $table->unique(['item_id', 'sender_id', 'receiver_id']);
        });

        // 2. ตารางข้อความ
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->text('body');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};