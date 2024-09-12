<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
    ];

    public function activites(): BelongsToMany
    {
        return $this->belongsToMany(Activite::class);
    }
}
