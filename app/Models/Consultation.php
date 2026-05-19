<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'order_id',
        'diagnosis',
        'treatment',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
