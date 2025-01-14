<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionsActivites extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'activite_id'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }
}
