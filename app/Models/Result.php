<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    // 按你现在的使用方式，其实没用到 fillable，但放着也没问题
    protected $fillable = [
        'decision_id',
        'score',
        'feedback',
    ];

    /**
     * 关联：一个 Result 属于一个 Decision
     */
    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 项目结局生成器：根据 score 生成结局等级 / 标题 / 描述 / 颜色
    |--------------------------------------------------------------------------
    |
    | 使用方式（在 Blade 里）：
    |   $result->ending_level         // S / A / B / C  / null
    |   $result->ending_title         // "Outstanding Project Success!" 等
    |   $result->ending_description   // 一整段结局说明
    |   $result->ending_badge_class   // Tailwind CSS class
    |
    | 如果 score 为 null，则这些属性返回 null / 提示未评分的文字。
    */

    public function getEndingLevelAttribute(): ?string
    {
        if ($this->score === null) {
            return null;
        }

        if ($this->score >= 90) {
            return 'S';
        } elseif ($this->score >= 75) {
            return 'A';
        } elseif ($this->score >= 60) {
            return 'B';
        } elseif ($this->score >= 40) {
            return 'C';
        } else {
            return 'D';
        }
    }

    public function getEndingTitleAttribute(): ?string
    {
        if ($this->score === null) {
            return 'Not graded yet';
        }

        switch ($this->ending_level) {
            case 'S':
                return 'Outstanding Project Success!';
            case 'A':
                return 'Strong Project Delivery';
            case 'B':
                return 'Acceptable but Risky Outcome';
            case 'C':
                return 'Troubled Project, Lessons Learned';
            case 'D':
            default:
                return 'Project Failure – Critical Review Needed';
        }
    }

    public function getEndingDescriptionAttribute(): ?string
    {
        if ($this->score === null) {
            return 'Once this decision is graded, a detailed project outcome summary will appear here.';
        }

        // 可以根据需要写得更“故事化”一点
        switch ($this->ending_level) {
            case 'S':
                return 'Your team delivered the project ahead of schedule, under budget, and with very high stakeholder satisfaction. Risks were well controlled and communication was proactive. This is the kind of project every sponsor dreams about.';
            case 'A':
                return 'The project was delivered on time with only minor deviations in budget and scope. Stakeholders are happy overall and the team maintained good collaboration. A few issues appeared, but they were resolved without major impact.';
            case 'B':
                return 'The project reached the finish line, but there were noticeable delays and some scope compromises. Budget control and risk planning could be improved. Stakeholders accept the result, but confidence in the team is mixed.';
            case 'C':
                return 'The project faced significant delays, overruns or misalignment with stakeholder expectations. Some objectives were met, but the lack of risk management and communication caused serious issues. This project should be used as a learning case.';
            case 'D':
            default:
                return 'The project failed to achieve its key objectives. Deadlines were missed, budget was not controlled, and stakeholder satisfaction is very low. A full post-mortem is required to understand what went wrong and how to avoid this in future projects.';
        }
    }

    public function getEndingBadgeClassAttribute(): string
    {
        // 返回 Tailwind CSS class，用于在 Blade 里套颜色
        if ($this->score === null) {
            return 'bg-gray-100 text-gray-700 border border-gray-300';
        }

        switch ($this->ending_level) {
            case 'S':
                return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
            case 'A':
                return 'bg-green-100 text-green-800 border border-green-300';
            case 'B':
                return 'bg-yellow-100 text-yellow-800 border border-yellow-300';
            case 'C':
                return 'bg-orange-100 text-orange-800 border border-orange-300';
            case 'D':
            default:
                return 'bg-red-100 text-red-800 border border-red-300';
        }
    }
}
