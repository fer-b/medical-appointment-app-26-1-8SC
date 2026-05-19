<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientCategory extends Model
{
    protected $table = 'client_categories';

    // Relación uno a muchos con clientes
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
