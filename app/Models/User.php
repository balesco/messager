<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use HasFactory;
    use Searchable;
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'group_id'];

    protected $searchableFields = ['*'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return asset('images/user.png');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messages2()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'admin_id');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
