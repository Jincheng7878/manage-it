<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'decision_id', 'score', 'feedback'
    ];

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }
}
