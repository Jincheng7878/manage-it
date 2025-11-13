<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Scenario;
use App\Models\Decision;
use App\Models\Result;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1) 管理员
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2) 普通学生用户
        $student = User::factory()->create([
            'name' => 'Student A',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 3) 教师（可作为另一个角色，如果需要）
        $teacher = User::factory()->create([
            'name' => 'Lecturer',
            'email' => 'lecturer@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // 也用 admin 表示有更多权限
        ]);

        // 4) 场景（由 admin 创建）
        $scenarios = Scenario::factory()->count(6)->create([
            'created_by' => $admin->id
        ]);

        // 5) 随机为每个场景创建若干决策与结果
        foreach ($scenarios as $scenario) {
            $decisions = Decision::factory()->count(2)->create([
                'scenario_id' => $scenario->id,
                'user_id' => $student->id,
            ]);

            foreach ($decisions as $d) {
                // 简单评分（复用 DecisionController 的算法思想）
                $base = 60;
                $time = max(0, 20 - abs($d->time_alloc - $scenario->duration) * 0.5);
                $budget = max(0, 20 - abs($d->cost_alloc - $scenario->budget) / max(1, $scenario->budget) * 20);
                $risk = $d->risk_level === 'high' ? -20 : ($d->risk_level === 'medium' ? -5 : 0);
                $score = max(0, min(100, intval($base + $time + $budget + $risk)));

                $feedback = match(true) {
                    $score >= 80 => 'Excellent strategic decision — very high success potential.',
                    $score >= 60 => 'Good decision with manageable risks.',
                    $score >= 40 => 'Moderate — consider adjusting time or cost allocation.',
                    default => 'High failure risk — review your approach carefully.',
                };

                Result::create([
                    'decision_id' => $d->id,
                    'score' => $score,
                    'feedback' => $feedback,
                ]);
            }
        }

        // 输出提示（运行时可在 console 查看）
        $this->command->info('DemoSeeder completed: admin@example.com / password ; student@example.com / password');
    }
}
