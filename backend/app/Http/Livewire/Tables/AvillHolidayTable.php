<?php

namespace App\Http\Livewire\Tables;

use App\Models\AvillHoliday;
use Exception;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AvillHolidayTable extends BaseDataTableComponent
{
    public $model = AvillHoliday::class;

    public function query() { return AvillHoliday::query(); }
    public function setTableRowClass($row): ?string { return $row->is_active ? null : 'inactive-item'; }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Fecha'), 'date')->searchable()->sortable(),
            Column::make(__('Nombre'), 'name')->searchable()->sortable(),
            Column::make(__('País'), 'country_code')->searchable()->sortable(),
            Column::make(__('Departamento'), 'department')->searchable()->sortable(),
            Column::make(__('Ciudad'), 'city')->searchable()->sortable(),
            Column::make(__('Aplica a'), 'applies_to')->searchable()->sortable(),
            Column::make(__('Activo'), 'is_active')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            $this->actionsColumn(),
        ];
    }

    public function deleteModel()
    {
        try { $this->selectedModel->delete(); $this->showSuccessAlert(__('Festivo eliminado correctamente')); }
        catch (Exception $e) { $this->showErrorAlert($e->getMessage() ?? __('No se pudo eliminar el festivo')); }
    }
}
