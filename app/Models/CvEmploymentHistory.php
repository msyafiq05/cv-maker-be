<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvEmploymentHistory extends Model
{
    protected $table = 'cv_employment_history';

    protected $fillable = [
        'cv_project_id',
        'company_name',
        'job_title',
        'start_year',
        'end_year',
        'company_location',
        'company_description',
    ];

    public function cvProject(): BelongsTo
    {
        return $this->belongsTo(CvProject::class);
    }
}
