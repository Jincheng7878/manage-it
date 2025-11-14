<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'budget',
        'duration',
        'difficulty',
        'initial_metrics',
        'created_by',

        // uploads
        'image_path',
        'file_path',
        'video_path',
        'video_url',

        // status
        'status',
        'deadline',
    ];

    protected $casts = [
        'initial_metrics' => 'array',
        'deadline'        => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Created by teacher
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Student decisions
    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }

    // ⭐ NEW: Comments (discussion)
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Scenario Open/Closed Logic
    |--------------------------------------------------------------------------
    */
    public function isOpenForSubmission(): bool
    {
        // manually closed
        if ($this->status === 'closed') {
            return false;
        }

        // expired deadline
        if ($this->deadline instanceof Carbon && now()->greaterThan($this->deadline)) {
            return false;
        }

        return true;
    }
}
