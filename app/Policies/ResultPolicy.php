<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;

class ResultPolicy
{
    /**
     * 只有提交该决策的学生本人 或 管理员 能查看这个结果
     */
    public function view(User $user, Result $result): bool
    {
        // 决策的提交者
        $decisionOwnerId = $result->decision->user_id ?? null;

        return $user->role === 'admin'
            || $user->id === $decisionOwnerId;
    }
}
