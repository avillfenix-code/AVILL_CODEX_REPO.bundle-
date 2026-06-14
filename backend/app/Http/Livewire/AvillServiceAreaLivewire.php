<?php

namespace App\Http\Livewire;

use App\Models\AvillServiceArea;
use Exception;
use Illuminate\Support\Facades\DB;

class AvillServiceAreaLivewire extends BaseLivewireComponent
{
    public $model = AvillServiceArea::class;

    public $name;
    public $type = 'barrio';
    public $city = 'Quibdó';
    public $department = 'Chocó';
    public $country_code = 'CO';
    public $map_polygon;
    public $isActive = true;

    public $zoneTypes = [];

    public function mount()
    {
        $this->loadOptions();
    }

    public function render()
    {
        $this->loadOptions();
        return view('livewire.avill_service_areas');
    }

    protected function rules()
    {
        return [
            'name'         => 'required|string|max:150',
            'type'         => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'department'   => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:5',
            'map_polygon'  => 'nullable|json',
        ];
    }

    public function initiateEdit($id)
    {
        $m = $this->model::find($id);
        $this->selectedModel  = $m;
        $this->name           = $m->name;
        $this->type           = $m->type;
        $this->city           = $m->city;
        $this->department     = $m->department;
        $this->country_code   = $m->country_code;
        $this->map_polygon    = $m->map_polygon ? json_encode($m->map_polygon) : null;
        $this->isActive       = $m->is_active;
        $this->emit('showEditModal');
    }

    public function save()
    {
        $this->validate();
        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->saveModel(new AvillServiceArea());
            DB::commit();
            $this->dismissModal();
            $this->resetFields();
            $this->showSuccessAlert(__('Zona creada correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo crear la zona'));
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
            $this->showSuccessAlert(__('Zona actualizada correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo actualizar la zona'));
        }
    }

    private function saveModel(AvillServiceArea $model)
    {
        $model->name         = $this->name;
        $model->type         = $this->type;
        $model->city         = $this->city;
        $model->department   = $this->department;
        $model->country_code = $this->country_code;
        $model->map_polygon  = $this->map_polygon ? json_decode($this->map_polygon, true) : null;
        $model->is_active    = $this->isActive;
        $model->save();
    }

    private function loadOptions()
    {
        $this->zoneTypes = [
            ['id' => 'barrio',   'name' => 'Barrio'],
            ['id' => 'comuna',   'name' => 'Comuna'],
            ['id' => 'zona',     'name' => 'Zona'],
            ['id' => 'ciudad',   'name' => 'Ciudad'],
            ['id' => 'municipio','name' => 'Municipio'],
        ];
    }

    private function resetFields()
    {
        $this->name = $this->map_polygon = null;
        $this->type = 'barrio';
        $this->city = 'Quibdó';
        $this->department = 'Chocó';
        $this->country_code = 'CO';
        $this->isActive = true;
    }
}
