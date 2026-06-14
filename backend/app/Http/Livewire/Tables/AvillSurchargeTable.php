<?php

namespace App\Http\Livewire\Tables;

use App\Models\AvillSurcharge;
use Exception;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AvillSurchargeTable extends BaseDataTableComponent
{
    public $model = AvillSurcharge::class;

    public function query() { return AvillSurcharge::query(); }
    public function setTableRowClass($row): ?string { return $row->is_active ? null : 'inactive-item'; }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Nombre'), 'name')->searchable()->sortable(),
            Column::make(__('Servicio'), 'service_type')->searchable()->sortable(),
            Column::make(__('Vehículo'), 'vehicle_mode')->searchable()->sortable(),
            Column::make(__('Valor'), 'amount')->sortable(),
            Column::make(__('Noche'), 'applies_night')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            Column::make(__('Domingo'), 'applies_sunday')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            Column::make(__('Festivo'), 'applies_holiday')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            Column::make(__('Activo'), 'is_active')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            $this->actionsColumn(),
        ];
    }

    public function deleteModel()
    {
        try { $this->selectedModel->delete(); $this->showSuccessAlert(__('Recargo eliminado correctamente')); }
        catch (Exception $e) { $this->showErrorAlert($e->getMessage() ?? __('No se pudo eliminar el recargo')); }
    }
}
