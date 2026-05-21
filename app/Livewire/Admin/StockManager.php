<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class StockManager extends Component
{
    use \App\Traits\ManagesBeerStock;

    public $sixStock;
    public $caguamaStock;

    public function mount()
    {
        $this->loadCurrentStock();
    }

    public function loadCurrentStock()
    {
        $stock = $this->getStockData();
        $this->sixStock = $stock['six'];
        $this->caguamaStock = $stock['caguama'];
    }

    public function saveStock()
    {
        $this->validate([
            'sixStock' => 'required|integer|min:0',
            'caguamaStock' => 'required|integer|min:0',
        ], [
            'sixStock.required' => 'El stock de Six Pack es obligatorio.',
            'sixStock.integer' => 'El stock de Six Pack debe ser un número entero.',
            'sixStock.min' => 'El stock de Six Pack no puede ser negativo.',
            'caguamaStock.required' => 'El stock de Caguama es obligatorio.',
            'caguamaStock.integer' => 'El stock de Caguama debe ser un número entero.',
            'caguamaStock.min' => 'El stock de Caguama no puede ser negativo.',
        ]);

        $this->updateStockData($this->sixStock, $this->caguamaStock);
        $this->loadCurrentStock();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Inventario Actualizado!',
            'text' => 'Los niveles de stock de cerveza artesanal se guardaron correctamente.'
        ]);
    }

    public function adjustSix($amount)
    {
        $this->sixStock = max(0, $this->sixStock + $amount);
        $this->updateStockData($this->sixStock, $this->caguamaStock);
        $this->loadCurrentStock();
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Stock Ajustado',
            'text' => "Se ha ajustado el stock de Six Packs en: " . ($amount > 0 ? "+{$amount}" : $amount) . " piezas."
        ]);
    }

    public function adjustCaguama($amount)
    {
        $this->caguamaStock = max(0, $this->caguamaStock + $amount);
        $this->updateStockData($this->sixStock, $this->caguamaStock);
        $this->loadCurrentStock();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Stock Ajustado',
            'text' => "Se ha ajustado el stock de Caguamas en: " . ($amount > 0 ? "+{$amount}" : $amount) . " piezas."
        ]);
    }

    public function render()
    {
        return view('livewire.admin.stock-manager')->layout('layouts.admin', [
            'title' => 'Gestión de Inventario / Stock',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'route' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Inventario',
                ],
            ]
        ]);
    }
}
