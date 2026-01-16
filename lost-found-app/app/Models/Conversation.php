<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['item_id', 'sender_id', 'receiver_id'];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    // Helper: หาว่าอีกฝ่ายคือใคร (ที่ไม่ใช่เรา)
    public function otherUser() {
        if (auth()->id() == $this->sender_id) {
            return $this->receiver;
        }
        return $this->sender;
    }
}