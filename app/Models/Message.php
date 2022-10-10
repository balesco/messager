<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'content',
        'sender_id',
        'receiver_id',
        'group_id',
        'message_id',
    ];

    protected $searchableFields = ['*'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
