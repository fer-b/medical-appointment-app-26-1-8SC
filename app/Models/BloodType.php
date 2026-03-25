<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodType extends Model
{
    // Relación uno a muchos con pacientes
    public function patients()
    {
        return $this->HasMany(Patient::class);
    }
}
