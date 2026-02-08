<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ สำหรับ Cloudinary v3.0: ไม่ต้องใส่ use MediaAlly ตรงนี้แล้วครับ
    // เราจะบันทึกแค่ URL ลงใน database ธรรมดาเลย

    // อนุญาตให้แก้ไขข้อมูลในคอลัมน์เหล่านี้ได้
    protected $fillable = [
        'user_id',
        'reporter_name',
        'phone_number',
        'title',
        'category',
        'description',
        'type',
        'status',
        'image_path',
        'location_text',
        'province',
        'latitude',
        'longitude',
        'event_date',
    ];

    // แปลงข้อมูลวันที่ให้อัตโนมัติ
    protected $casts = [
        'event_date' => 'date',
    ];

    // Scope นี้จะช่วยกรองเอาเฉพาะรายการที่สถานะเป็น active
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // เชื่อมความสัมพันธ์กับ User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}