<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;

class ClientTable extends DataTableComponent
{
    // protected $model = Client::class;

    // Este método define el modelo
    public function builder(): Builder
    {
        // Devuelve la relación con roles
        return Client::query()
        ->with('user');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "user.name")
                ->sortable(),
            Column::make("Email", "user.email")
                ->sortable(),
            Column::make("Número de ID", "user.id_number")
                ->sortable(),
            Column::make("Teléfono", "user.phone")
                ->sortable(),

            Column::make("Acciones")
                ->label(function($row){
                    return view('admin.clients.actions',
                ['client' => $row]);
                })
        ];
    }
}
