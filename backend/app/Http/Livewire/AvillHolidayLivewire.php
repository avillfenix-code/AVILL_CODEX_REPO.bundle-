<?php

namespace App\Http\Livewire;

use App\Models\AvillHoliday;
use Exception;
use Illuminate\Support\Facades\DB;

class AvillHolidayLivewire extends BaseLivewireComponent
{
    public $model = AvillHoliday::class;

    public $date;
    public $name;
    public $country_code = 'CO';
    public $department;
    public $city;
    public $applies_to = 'todos';
    public $isActive = true;

    public $appliesToOptions = [];

    public function mount()
    {
        $this->loadOptions();
    }

    public function render()
    {
        $this->loadOptions();
        return view('livewire.avill_holidays');
    }

    protected function rules()
    {
        return [
            'date'         => 'required|date',
            'name'         => 'required|string|max:150',
            'country_code' => 'nullable|string|max:5',
            'department'   => 'nullable|string|max:100',
            'city'         => 'nullable|string|max:100',
            'applies_to'   => 'nullable|string',
        ];
    }

    public function initiateEdit($id)
    {
        $m = $this->model::find($id);
        $this->selectedModel  = $m;
        $this->date           = $m->date->format('Y-m-d');
        $this->name           = $m->name;
        $this->country_code   = $m->country_code;
        $this->department     = $m->department;
        $this->city           = $m->city;
        $this->applies_to     = $m->applies_to;
        $this->isActive       = $m->is_active;
        $this->emit('showEditModal');
    }

    public function save()
    {
        $this->validate();
        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->saveModel(new AvillHoliday());
            DB::commit();
            $this->dismissModal();
            $this->resetFields();
            $this->showSuccessAlert(__('Festivo creado correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo crear el festivo'));
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
            $this->showSuccessAlert(__('Festivo actualizado correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo actualizar el festivo'));
        }
    }

    private function saveModel(AvillHoliday $model)
    {
        $model->date         = $this->date;
        $model->name         = $this->name;
        $model->country_code = $this->country_code ?? 'CO';
        $model->department   = $this->department;
        $model->city         = $this->city;
        $model->applies_to   = $this->applies_to ?? 'todos';
        $model->is_active    = $this->isActive;
        $model->save();
    }

    private function loadOptions()
    {
        $this->appliesToOptions = [
            ['id' => 'todos',    'name' => 'Todos los servicios'],
            ['id' => 'taxi',     'name' => 'Taxi / Transporte'],
            ['id' => 'delivery', 'name' => 'Domicilios'],
            ['id' => 'package',  'name' => 'Encomiendas'],
        ];
    }

    private function resetFields()
    {
        $this->date = $this->name = $this->department = $this->city = null;
        $this->country_code = 'CO';
        $this->applies_to = 'todos';
        $this->isActive = true;
    }
}
