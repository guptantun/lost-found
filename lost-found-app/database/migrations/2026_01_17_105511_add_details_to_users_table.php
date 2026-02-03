<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable()->after('email'); // เบอร์โทร
        $table->text('bio')->nullable()->after('phone'); // เกี่ยวกับฉัน
        $table->string('facebook')->nullable()->after('bio'); // ลิงก์เฟสบุ๊ค
        $table->string('line_id')->nullable()->after('facebook'); // ไอดีไลน์
        $table->string('avatar')->nullable()->after('line_id'); // รูปโปรไฟล์ (เผื่อไว้)
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['phone', 'bio', 'facebook', 'line_id', 'avatar']);
    });
}
};
