<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvOrganization extends Model
{
    protected $fillable = [
        'cv_project_id',
        'organization_name',
        'role',
        'start_year',
        'end_year',
        'location',
        'description',
    ];

    public function cvProject(): BelongsTo
    {
        return $this->belongsTo(CvProject::class);
    }
}
