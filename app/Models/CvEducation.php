<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvEducation extends Model
{
    protected $table = 'cv_education';

    protected $fillable = [
        'cv_project_id',
        'institution_name',
        'degree',
        'field_of_study',
        'start_year',
        'end_year',
        'gpa',
        'location',
        'description',
    ];

    public function cvProject(): BelongsTo
    {
        return $this->belongsTo(CvProject::class);
    }
}
