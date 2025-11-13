<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'file_path',      // ← NEW: uploaded file path
    ];

    protected $casts = [
        'initial_metrics' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }
}
