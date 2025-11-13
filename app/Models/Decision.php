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

        // core decision fields
        'strategy',
        'time_alloc',
        'cost_alloc',
        'risk_level',
        'notes',

        // file upload (student attachment)
        'file_path',

        // structured decision modelling fields
        'swot_strengths',
        'swot_weaknesses',
        'swot_opportunities',
        'swot_threats',
        'wbs',
        'risk_matrix',
        'cost_breakdown',
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
