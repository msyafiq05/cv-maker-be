<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvPersonalDetail extends Model
{
    protected $fillable = [
        'cv_project_id',
        'full_name',
        'phone_number',
        'email_address',
        'place_of_birth',
        'date_of_birth',
        'address',
        'website_url',
        'short_description',
        'foto_profil',
    ];

    /**
     * Personal detail milik satu CV project.
     */
    public function cvProject(): BelongsTo
    {
        return $this->belongsTo(CvProject::class);
    }
}
