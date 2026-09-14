<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    // Deze velden mogen via Project::create() worden ingevuld.
    protected $fillable = ['title', 'description'];

    public function images(): HasMany
    {
        // Afbeeldingen worden standaard in de ingestelde volgorde opgehaald.
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
