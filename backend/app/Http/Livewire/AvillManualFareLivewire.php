<?php

namespace App\Http\Livewire;

use App\Models\AvillManualFare;
use App\Models\AvillServiceArea;
use Exception;
use Illuminate\Support\Facades\DB;

class AvillManualFareLivewire extends BaseLivewireComponent
{
    public $model = AvillManualFare::class;

    public $origin_area_id;
    public $destination_area_id;
    public $service_type = 'taxi_urbano';
    public $vehicle_mode = 'carro';
    public $pricing_mode = AvillManualFare::PRICING_MODE_FIXED;
    public $base_amount = 0;
    public $minimum_amount = 0;
    public $management_surcharge_amount = 0;
    public $night_surcharge_amount = 0;
    public $holiday_surcharge_amount = 0;
    public $rain_surcharge_amount = 0;
    public $additional_km_amount = 0;
    public $isActive = true;

    public $areas = [];
    public $serviceTypes = [];
    public $vehicleModes = [];
    public $pricingModes = [];

    public function mount()
    {
        $this->loadOptions();
    }

    public function render()
    {
        $this->loadOptions();
        return view('livewire.avill_manual_fares');
    }

    protected function rules()
    {
        return [
            'origin_area_id'               => 'required|exists:avill_service_areas,id',
            'destination_area_id'          => 'required|exists:avill_service_areas,id',
            'service_type'                 => 'required|string',
            'vehicle_mode'                 => 'required|string',
            'pricing_mode'                 => 'required|in:tarifa_fija,cotizacion_manual',
            'base_amount'                  => 'required|numeric|min:0',
            'minimum_amount'               => 'nullable|numeric|min:0',
            'management_surcharge_amount'  => 'nullable|numeric|min:0',
            'night_surcharge_amount'       => 'nullable|numeric|min:0',
            'holiday_surcharge_amount'     => 'nullable|numeric|min:0',
            'rain_surcharge_amount'        => 'nullable|numeric|min:0',
            'additional_km_amount'         => 'nullable|numeric|min:0',
        ];
    }

    public function initiateEdit($id)
    {
        $m = $this->model::find($id);
        $this->selectedModel         = $m;
        $this->origin_area_id        = $m->origin_area_id;
        $this->destination_area_id   = $m->destination_area_id;
        $this->service_type          = $m->service_type;
        $this->vehicle_mode          = $m->vehicle_mode;
        $this->pricing_mode          = $m->pricing_mode;
        $this->base_amount           = $m->base_amount;
        $this->minimum_amount        = $m->minimum_amount;
        $this->management_surcharge_amount = $m->management_surcharge_amount;
        $this->night_surcharge_amount    = $m->night_surcharge_amount;
        $this->holiday_surcharge_amount  = $m->holiday_surcharge_amount;
        $this->rain_surcharge_amount     = $m->rain_surcharge_amount;
        $this->additional_km_amount      = $m->additional_km_amount;
        $this->isActive = $m->is_active;
        $this->emit('showEditModal');
    }

    public function save()
    {
        $this->validate();
        try {
            $this->isDemo();
            DB::beginTransaction();
            $this->saveModel(new AvillManualFare());
            DB::commit();
            $this->dismissModal();
            $this->resetFields();
            $this->showSuccessAlert(__('Tarifa creada correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo crear la tarifa'));
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
            $this->showSuccessAlert(__('Tarifa actualizada correctamente'));
            $this->emit('refreshTable');
        } catch (Exception $e) {
            DB::rollback();
            $this->showErrorAlert($e->getMessage() ?? __('No se pudo actualizar la tarifa'));
        }
    }

    private function saveModel(AvillManualFare $model)
    {
        $model->origin_area_id               = $this->origin_area_id;
        $model->destination_area_id          = $this->destination_area_id;
        $model->service_type                 = $this->service_type;
        $model->vehicle_mode                 = $this->vehicle_mode;
        $model->pricing_mode                 = $this->pricing_mode;
        $model->base_amount                  = $this->base_amount ?? 0;
        $model->minimum_amount               = $this->minimum_amount ?? 0;
        $model->management_surcharge_amount  = $this->management_surcharge_amount ?? 0;
        $model->night_surcharge_amount       = $this->night_surcharge_amount ?? 0;
        $model->holiday_surcharge_amount     = $this->holiday_surcharge_amount ?? 0;
        $model->rain_surcharge_amount        = $this->rain_surcharge_amount ?? 0;
        $model->additional_km_amount         = $this->additional_km_amount ?? 0;
        $model->is_active                    = $this->isActive;
        $model->save();
    }

    private function loadOptions()
    {
        $this->areas = AvillServiceArea::active()->orderBy('name')->get(['id', 'name'])
            ->map(fn ($a) => ['id' => $a->id, 'name' => $a->name])->toArray();

        $this->serviceTypes = [
            ['id' => 'taxi_urbano',               'name' => 'Taxi urbano'],
            ['id' => 'rapimoto_mototaxi',         'name' => 'Rapimoto / Mototaxi'],
            ['id' => 'domicilio',                  'name' => 'Domicilios'],
            ['id' => 'motocarro',                  'name' => 'Motocarro'],
            ['id' => 'mudanza',                    'name' => 'Mudanzas'],
            ['id' => 'puerta_a_puerta',            'name' => 'Puerta a puerta'],
        ];

        $this->vehicleModes = [
            ['id' => 'carro',             'name' => 'Carro'],
            ['id' => 'moto',              'name' => 'Moto'],
            ['id' => 'repartidor',        'name' => 'Repartidor'],
            ['id' => 'motocarro',         'name' => 'Motocarro'],
            ['id' => 'camion',            'name' => 'Camión'],
        ];

        $this->pricingModes = [
            ['id' => AvillManualFare::PRICING_MODE_FIXED,        'name' => 'Tarifa fija'],
            ['id' => AvillManualFare::PRICING_MODE_MANUAL_QUOTE, 'name' => 'Cotización manual'],
        ];
    }

    private function resetFields()
    {
        $this->origin_area_id = $this->destination_area_id = null;
        $this->service_type = 'taxi_urbano';
        $this->vehicle_mode = 'carro';
        $this->pricing_mode = AvillManualFare::PRICING_MODE_FIXED;
        $this->base_amount = $this->minimum_amount = $this->management_surcharge_amount = 0;
        $this->night_surcharge_amount = $this->holiday_surcharge_amount = $this->rain_surcharge_amount = $this->additional_km_amount = 0;
        $this->isActive = true;
    }
}
