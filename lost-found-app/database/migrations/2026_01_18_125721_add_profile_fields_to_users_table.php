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
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone')->nullable()->after('email'); // เบอร์โทร
        $table->string('line_id')->nullable()->after('phone'); // Line ID
        $table->string('facebook')->nullable()->after('line_id'); // Facebook Link
        $table->text('bio')->nullable()->after('facebook'); // คำแนะนำตัว
        $table->string('avatar')->nullable()->after('bio'); // รูปโปรไฟล์ (เก็บชื่อไฟล์)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
