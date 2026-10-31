<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'name',
        'district',
        'risk_level',
        'contact_agency',
        'description',
    ];

    public function fireReports(): HasMany
    {
        return $this->hasMany(FireReport::class, 'area_id');
    }
}
