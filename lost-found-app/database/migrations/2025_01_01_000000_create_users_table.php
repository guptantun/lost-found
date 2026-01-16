<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // เพิ่มฟิลด์สำหรับระบบ Lost & Found
            $table->string('role')->default('member'); // member หรือ admin
            $table->integer('trust_score')->default(100); // คะแนนความน่าเชื่อถือ
            $table->boolean('is_verified')->default(false); // ยืนยันตัวตนหรือยัง
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};