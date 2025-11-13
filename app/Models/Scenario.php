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

        // teacher uploads
        'image_path',
        'file_path',

        // open / closed + deadline
        'status',
        'deadline',
    ];

    protected $casts = [
        'initial_metrics' => 'array',
        'deadline'        => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }

    /**
     * Is this scenario open for student submissions?
     */
    public function isOpenForSubmission(): bool
    {
        // hard closed
        if ($this->status === 'closed') {
            return false;
        }

        // deadline passed
        if ($this->deadline instanceof Carbon && now()->greaterThan($this->deadline)) {
            return false;
        }

        return true;
    }
}
