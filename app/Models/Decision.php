<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_id',
        'user_id',
        'strategy',
        'time_alloc',
        'cost_alloc',
        'risk_level',
        'notes',
        'file_path',    // ← NEW: uploaded file path
    ];

    public function scenario()
    {
        return $this->belongsTo(Scenario::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }
}
