<?php

namespace App\Http\Livewire;

use App\Models\AvillSurcharge;
use Exception;
use Illuminate\Support\Facades\DB;

class AvillSurchargeLivewire extends BaseLivewireComponent
{
    public $model = AvillSurcharge::class;

    public $name;
    public $service_type = '';
    public $vehicle_mode = '';
    public $amount = 0;
    public $applies_night = false;
    public $applies_sunday = false;
    public $applies_holiday = false;
    public $isActive = true;

    public $serviceTypes = [];

    public function mount()
    {
        $this->loadOptions();
    }

    public function render()
    {
        $this->loadOptions();
        return view('livewire.avill_surcharges');
    }

    protected function rules()
    {
        return [
            'name'           => 'required|string|max:150',
            'service_type'   => 'nullable|string',
            'vehicle_mode'   => 'nullable|string',
            'amount'         => 'required|numeric|min:0',
            'applies_night'  => 'boolean',
            'applies_sunday' => 'boolean',
            'applies_holiday'=> 'boolean',
        ];
    }

    public function initiateEdit($id)
    {
        $m = $this->model::find($id);
        $this->selectedModel     = $m;
        $this->name              = $m->name;
        $this->service_type      = $m->service_type;
        $this->vehicle_mode      = $m->vehicle_mode;
        $this->amount            = $m->amount;
        $this->applies_night     = $m->applies_night;
        $this->applies_sunday    = $m->applies_sunday;
        $this->applies_holiday   = $m->applies_holiday;
        $this->isActive          = $m->is_active;
        $this->emit('showEditModal');
    }

    public function save()
    {
        $this->validate();
        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->saveModel(new AvillSurcharge());
            DB::commit();
            $this->dismissModal();
            $this->resetFields();
            $this->showSuccessAlert(__('Recargo creado correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo crear el recargo'));
        }
    }

    public function update()
    {
        $this->validate();
        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->saveModel($this->selectedModel);
            DB::commit();
            $this->dismissModal();
            $this->resetFields();
            $this->showSuccessAlert(__('Recargo actualizado correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo actualizar el recargo'));
        }
    }

    private function saveModel(AvillSurcharge $model)
    {
        $model->name             = $this->name;
        $model->service_type     = $this->service_type ?: null;
        $model->vehicle_mode     = $this->vehicle_mode ?: null;
        $model->amount           = $this->amount;
        $model->applies_night    = $this->applies_night;
        $model->applies_sunday   = $this->applies_sunday;
        $model->applies_holiday  = $this->applies_holiday;
        $model->is_active        = $this->isActive;
        $model->save();
    }

    private function loadOptions()
    {
        $this->serviceTypes = [
            ['id' => '',                  'name' => 'Todos los servicios'],
            ['id' => 'taxi_urbano',       'name' => 'Taxi urbano'],
            ['id' => 'rapimoto_mototaxi', 'name' => 'Rapimoto / Mototaxi'],
            ['id' => 'domicilio',          'name' => 'Domicilios'],
            ['id' => 'motocarro',          'name' => 'Motocarro'],
        ];
    }

    private function resetFields()
    {
        $this->name = $this->service_type = $this->vehicle_mode = null;
        $this->amount = 0;
        $this->applies_night = $this->applies_sunday = $this->applies_holiday = false;
        $this->isActive = true;
    }
}
