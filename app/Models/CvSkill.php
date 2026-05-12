<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvSkill extends Model
{
    protected $fillable = [
        'cv_project_id',
        'activity_name',
        'year',
        'elaboration',
    ];

    /**
     * Skill milik satu CV project.
     */
    public function cvProject(): BelongsTo
    {
        return $this->belongsTo(CvProject::class);
    }
}
