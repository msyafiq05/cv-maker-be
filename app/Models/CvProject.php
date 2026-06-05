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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personalDetail(): HasOne
    {
        return $this->hasOne(CvPersonalDetail::class);
    }

    public function employmentHistories(): HasMany
    {
        return $this->hasMany(CvEmploymentHistory::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(CvEducation::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CvSkill::class);
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(CvOrganization::class);
    }
}
