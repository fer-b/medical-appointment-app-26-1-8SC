<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'consultation_id',
        'name',
        'dose',
        'frequency',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
