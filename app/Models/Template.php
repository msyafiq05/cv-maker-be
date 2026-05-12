<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = [
        'nama_template',
        'gambar_preview',
    ];

    /**
     * Template bisa dipakai oleh banyak CV projects.
     */
    public function cvProjects(): HasMany
    {
        return $this->hasMany(CvProject::class);
    }
}
