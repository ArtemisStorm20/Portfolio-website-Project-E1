<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    //Alleen het pad en de sorteerpositie worden automatisch ingevuld.
    //Het pad en de volgorde komen vanuit het uploadbeheer. De gekoppelde project-ID wordt automatisch door de relatie toegevoegd
    protected $fillable = ['path', 'sort_order'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
