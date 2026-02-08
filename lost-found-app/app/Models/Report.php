<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูล
    protected $fillable = ['user_id', 'item_id', 'reason', 'status'];

    // ความสัมพันธ์กับ User (คนแจ้ง)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์กับ Item (ประกาศที่ถูกแจ้ง)
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}