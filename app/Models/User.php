<?php

namespace App\Models;

use App\Models\Decision;
use App\Models\Result;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 批量赋值字段
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',   // 你之前已经有的角色字段
        'xp',     // ⭐ 新增：经验值
    ];

    /**
     * 隐藏字段
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /* ===========================
     *  关系
     * =========================== */

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }

    public function results()
    {
        // 一个用户通过 decision 拿到 result（间接关系）
        return $this->hasManyThrough(
            Result::class,
            Decision::class,
            'user_id',      // Decision 上的外键
            'decision_id',  // Result 上的外键
            'id',           // User 主键
            'id'            // Decision 主键
        );
    }

    /* ===========================
     *  计算属性：等级 / 升级所需 XP
     * =========================== */

    /**
     * 等级（根据 XP 计算）
     * 规则：每 50 XP 升一级
     */
    public function getLevelAttribute(): int
    {
        return intdiv($this->xp ?? 0, 50) + 1;
    }

    /**
     * 距离下一等级还差多少 XP
     */
    public function getXpToNextLevelAttribute(): int
    {
        $currentXp   = $this->xp ?? 0;
        $nextLevelXp = $this->level * 50;

        return max(0, $nextLevelXp - $currentXp);
    }

    /* ===========================
     *  计算属性：徽章系统
     * =========================== */

    /**
     * 根据 XP、决策数量、成绩等计算徽章
     */
    public function getBadgesAttribute(): array
    {
        $badges = [];

        $xp = $this->xp ?? 0;

        // 决策数量
        $decisionsCount = $this->decisions()->count();

        // 高分次数（>= 80）
        $highScoreCount = $this->results()
            ->where('score', '>=', 80)
            ->count();

        // 1) 新手徽章
        if ($decisionsCount >= 1) {
            $badges[] = 'Getting Started';
        }

        // 2) 场景探索者
        if ($decisionsCount >= 5) {
            $badges[] = 'Scenario Explorer';
        }

        // 3) 高分玩家
        if ($highScoreCount >= 3) {
            $badges[] = 'High Scorer';
        }

        // 4) XP 达到一定数量给一个“Risk Master”
        if ($xp >= 200) {
            $badges[] = 'Risk Master';
        }

        // 5) XP 很高再给一个“Budget Expert”
        if ($xp >= 350) {
            $badges[] = 'Budget Expert';
        }

        // 如果什么成就都没有，给一个默认
        if (empty($badges)) {
            $badges[] = 'Rookie';
        }

        return $badges;
    }
}
