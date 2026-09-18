<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    //Deze velden mogen worden ingevuld via Project::create(). 
    // Alleen deze velden worden toegestaan, zodat invoer vanuit het beheer geen andere velden van het model kan aanpassen.
    protected $fillable = ['title', 'description'];

    public function images(): HasMany
    {
        // Afbeeldingen worden standaard in de ingestelde volgorde opgehaald.
        //Door een vaste volgorde te gebruiken, blijven de hoofdafbeelding en de volgorde van de galerij hetzelfde in de portfolio.
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        //Een project kan meerdere tags hebben. Deze tags kunnen ook bij andere projecten worden gebruikt via de koppeltabel.
        return $this->belongsToMany(Tag::class);
    }
}
