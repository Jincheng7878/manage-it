<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // ✅ 新增 role，可批量赋值
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 一个用户可以创建多个场景（老师 / 管理员）
    public function scenarios()
    {
        return $this->hasMany(\App\Models\Scenario::class, 'created_by');
    }

    // 一个用户可以提交多条决策（学生）
    public function decisions()
    {
        return $this->hasMany(\App\Models\Decision::class, 'user_id');
    }
}
