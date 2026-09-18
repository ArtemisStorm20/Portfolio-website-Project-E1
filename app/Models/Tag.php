<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    //Alleen de naam en de slug kunnen worden ingevuld.
    protected $fillable = ['name', 'slug'];

    public function projects(): BelongsToMany
    {
        //De many-to-many-relatie zorgt ervoor dat tags bij meerdere projecten gebruikt kunnen worden en maakt het filteren op tags mogelijk.
        return $this->belongsToMany(Project::class);
    }
}
