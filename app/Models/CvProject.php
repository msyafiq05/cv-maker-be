<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CvProject extends Model
{
    protected $fillable = [
        'user_id',
        'judul_cv',
    ];

    /**
     * CV project dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



    /**
     * CV project memiliki satu personal detail.
     */
    public function personalDetail(): HasOne
    {
        return $this->hasOne(CvPersonalDetail::class);
    }

    /**
     * CV project memiliki banyak employment history.
     */
    public function employmentHistories(): HasMany
    {
        return $this->hasMany(CvEmploymentHistory::class);
    }

    /**
     * CV project memiliki banyak education.
     */
    public function educations(): HasMany
    {
        return $this->hasMany(CvEducation::class);
    }

    /**
     * CV project memiliki banyak skills.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(CvSkill::class);
    }

    /**
     * CV project memiliki banyak organizations.
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(CvOrganization::class);
    }
}
