<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Order;

class OrderManager extends Component
{
    public Order $order;
    
    public $activeTab = 'detalles';
    
    // Detalles del Pedido
    public $details;
    public $extra_notes;
    public $notes;
    
    // Ticket / Productos
    public $products = [];
    
    public $isHistoryModalOpen = false;

    public function mount(Order $order)
    {
        $this->order = $order;
        
        // Cargar cliente completo y pedido si existe
        $this->order->load('client.user', 'employee.user', 'consultation.medicines');
        
        if ($this->order->consultation) {
            $this->details = $this->order->consultation->diagnosis;
            $this->extra_notes = $this->order->consultation->treatment;
            $this->notes = $this->order->consultation->notes;
            
            if ($this->order->consultation->medicines->count() > 0) {
                foreach ($this->order->consultation->medicines as $med) {
                    $this->products[] = [
                        'name' => $med->name,
                        'dose' => $med->dose,
                        'frequency' => $med->frequency,
                    ];
                }
            } else {
                $this->addProduct();
            }
        } else {
            // Inicializar con un producto vacío
            $this->addProduct();
        }
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function addProduct()
    {
        $this->products[] = [
            'name' => '',
            'dose' => '',
            'frequency' => ''
        ];
    }
    
    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }
    
    public function openHistoryModal()
    {
        $this->isHistoryModalOpen = true;
    }
    
    public function closeHistoryModal()
    {
        $this->isHistoryModalOpen = false;
    }
    
    public function saveConsultation()
    {
        $this->validate([
            'details' => 'required|string',
            'extra_notes' => 'required|string',
            'products.*.name' => 'nullable|string',
            'products.*.dose' => 'nullable|string',
            'products.*.frequency' => 'nullable|string',
        ]);
        
        $consultation = \App\Models\Consultation::updateOrCreate(
            ['order_id' => $this->order->id],
            [
                'diagnosis' => $this->details,
                'treatment' => $this->extra_notes,
                'notes' => $this->notes,
            ]
        );

        $consultation->medicines()->delete();

        foreach ($this->products as $productData) {
            if (!empty($productData['name'])) {
                \App\Models\Medicine::create([
                    'consultation_id' => $consultation->id,
                    'name' => $productData['name'],
                    'dose' => $productData['dose'] ?? '',
                    'frequency' => $productData['frequency'] ?? '',
                ]);
            }
        }
        
        // Cambiamos el estado de la cita a Completado
        $this->order->update(['status' => 2]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Guardado correctamente',
            'text' => 'Productos guardados correctamente.'
        ]);
        
        return redirect()->route('admin.orders.index');
    }

    public function render()
    {
        return view('livewire.admin.order-manager')->layout('layouts.admin', [
            'title' => 'Atender Pedido',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
                ['name' => 'Pedidos', 'route' => route('admin.orders.index')],
                ['name' => 'Detalles']
            ]
        ]);
    }
}
