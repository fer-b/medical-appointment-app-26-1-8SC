<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(OrderObserver::class)]
class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'client_id',
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
        'six_quantity',
        'caguama_quantity',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }
}