<?php

namespace App\Http\Livewire\Tables;

use App\Models\AvillManualFare;
use Exception;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AvillManualFareTable extends BaseDataTableComponent
{
    public $model = AvillManualFare::class;

    public function query()
    {
        return AvillManualFare::with(['origin_area', 'destination_area']);
    }

    public function setTableRowClass($row): ?string
    {
        return $row->is_active ? null : 'inactive-item';
    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Servicio'), 'service_type')->format(fn ($v) => $this->serviceName($v))->searchable()->sortable(),
            Column::make(__('Vehículo'), 'vehicle_mode')->format(fn ($v) => $this->vehicleName($v))->searchable()->sortable(),
            Column::make(__('Origen'), 'origin_area.name')->searchable(),
            Column::make(__('Destino'), 'destination_area.name')->searchable(),
            Column::make(__('Tarifa fija'), 'base_amount')->sortable(),
            Column::make(__('Gestión'), 'management_surcharge_amount')->sortable(),
            Column::make(__('Minima'), 'minimum_amount')->sortable(),
            Column::make(__('Km adicional'), 'additional_km_amount')->sortable(),
            Column::make(__('Noche'), 'night_surcharge_amount')->sortable(),
            Column::make(__('Festivo'), 'holiday_surcharge_amount')->sortable(),
            Column::make(__('Lluvia'), 'rain_surcharge_amount')->sortable(),
            Column::make(__('Activo'), 'is_active')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            $this->actionsColumn(),
        ];
    }

    public function deleteModel()
    {
        try {
            $this->selectedModel->delete();
            $this->showSuccessAlert(__('Tarifa eliminada correctamente'));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __('No se pudo eliminar la tarifa'));
        }
    }

    private function serviceName($value): string
    {
        return [
            'taxi_urbano' => 'Taxi/Transporte particular',
            'taxi_transporte_particular' => 'Taxi/Transporte particular',
            'rapimoto_mototaxi' => 'Rapimoto/Mototaxi',
            'domicilio' => 'Domicilios',
            'motocarro_mudanzas' => 'Motocarro/Mudanzas',
            'motocarro' => 'Motocarro',
            'mudanza' => 'Mudanzas',
            'puerta_a_puerta' => 'Puerta a puerta',
        ][$value] ?? $value;
    }

    private function vehicleName($value): string
    {
        return [
            'carro' => 'Carro', 'moto' => 'Moto', 'repartidor' => 'Repartidor',
            'motocarro' => 'Motocarro', 'mudanza' => 'Mudanza',
            'carro_publico' => 'Carro publico', 'carro_particular' => 'Carro particular', 'camion' => 'Camion',
        ][$value] ?? $value;
    }
}
