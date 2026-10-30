<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role','avatar','aktif','last_login',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login'        => 'datetime',
        'aktif'             => 'boolean',
    ];

    public function mahasiswa() { return $this->hasOne(Mahasiswa::class); }
    public function dosen()     { return $this->hasOne(Dosen::class); }

    public function getRoleBadgeAttribute(): array
    {
        return match($this->role) {
            'admin'     => ['label'=>'Admin',     'class'=>'bg-red-100 text-red-700'],
            'akademik'  => ['label'=>'Akademik',  'class'=>'bg-blue-100 text-blue-700'],
            'keuangan'  => ['label'=>'Keuangan',  'class'=>'bg-green-100 text-green-700'],
            'dosen'     => ['label'=>'Dosen',     'class'=>'bg-purple-100 text-purple-700'],
            'mahasiswa' => ['label'=>'Mahasiswa', 'class'=>'bg-indigo-100 text-indigo-700'],
            default     => ['label'=>ucfirst($this->role),'class'=>'bg-gray-100 text-gray-500'],
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4F46E5&color=fff&size=64';
    }
}
