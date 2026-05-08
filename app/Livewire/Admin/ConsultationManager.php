<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Appointment;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    
    public $activeTab = 'consulta';
    
    // Consulta
    public $diagnosis;
    public $treatment;
    public $notes;
    
    // Receta
    public $medicines = [];
    
    public $isHistoryModalOpen = false;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        
        // Cargar paciente completo y consulta si existe
        $this->appointment->load('patient.user', 'doctor.user', 'consultation.medicines');
        
        if ($this->appointment->consultation) {
            $this->diagnosis = $this->appointment->consultation->diagnosis;
            $this->treatment = $this->appointment->consultation->treatment;
            $this->notes = $this->appointment->consultation->notes;
            
            if ($this->appointment->consultation->medicines->count() > 0) {
                foreach ($this->appointment->consultation->medicines as $med) {
                    $this->medicines[] = [
                        'name' => $med->name,
                        'dose' => $med->dose,
                        'frequency' => $med->frequency,
                    ];
                }
            } else {
                $this->addMedicine();
            }
        } else {
            // Inicializar con un medicamento vacío
            $this->addMedicine();
        }
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function addMedicine()
    {
        $this->medicines[] = [
            'name' => '',
            'dose' => '',
            'frequency' => ''
        ];
    }
    
    public function removeMedicine($index)
    {
        unset($this->medicines[$index]);
        $this->medicines = array_values($this->medicines);
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
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'medicines.*.name' => 'nullable|string',
            'medicines.*.dose' => 'nullable|string',
            'medicines.*.frequency' => 'nullable|string',
        ]);
        
        $consultation = \App\Models\Consultation::updateOrCreate(
            ['appointment_id' => $this->appointment->id],
            [
                'diagnosis' => $this->diagnosis,
                'treatment' => $this->treatment,
                'notes' => $this->notes,
            ]
        );

        $consultation->medicines()->delete();

        foreach ($this->medicines as $medicineData) {
            if (!empty($medicineData['name'])) {
                \App\Models\Medicine::create([
                    'consultation_id' => $consultation->id,
                    'name' => $medicineData['name'],
                    'dose' => $medicineData['dose'] ?? '',
                    'frequency' => $medicineData['frequency'] ?? '',
                ]);
            }
        }
        
        // Cambiamos el estado de la cita a Completado
        $this->appointment->update(['status' => 2]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Guardado correctamente',
            'text' => 'Medicinas guardadas correctamente.'
        ]);
        
        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager')->layout('layouts.admin', [
            'title' => 'Atender Cita',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
                ['name' => 'Citas', 'route' => route('admin.appointments.index')],
                ['name' => 'Consulta']
            ]
        ]);
    }
}
