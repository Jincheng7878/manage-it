<?php

namespace App\Policies;

use App\Models\Scenario;
use App\Models\User;

class ScenarioPolicy
{
    /**
     * 所有人只要登录就可以查看场景列表或详情
     */
    public function view(User $user, Scenario $scenario): bool
    {
        return true;
    }

    /**
     * 允许哪些人创建场景
     * 这里示例：管理员和老师可以创建；普通学生也可以的话，就加 'user'
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'user']);
    }

    /**
     * 更新权限：只有创建者本人或 admin 可以编辑
     */
    public function update(User $user, Scenario $scenario): bool
    {
        return $user->id === $scenario->created_by || $user->role === 'admin';
    }

    /**
     * 删除权限：同样，只有创建者本人或 admin 可以删除
     */
    public function delete(User $user, Scenario $scenario): bool
    {
        return $user->id === $scenario->created_by || $user->role === 'admin';
    }
}
