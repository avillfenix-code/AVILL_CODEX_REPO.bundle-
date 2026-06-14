<?php

namespace App\Http\Livewire\Tables;

use App\Models\AvillServiceArea;
use Exception;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AvillServiceAreaTable extends BaseDataTableComponent
{
    public $model = AvillServiceArea::class;

    public function query() { return AvillServiceArea::query(); }

    public function setTableRowClass($row): ?string { return $row->is_active ? null : 'inactive-item'; }

    public function columns(): array
    {
        return [
            Column::make(__('ID'), 'id')->searchable()->sortable(),
            Column::make(__('Nombre'), 'name')->searchable()->sortable(),
            Column::make(__('Tipo'), 'type')->searchable()->sortable(),
            Column::make(__('Ciudad'), 'city')->searchable()->sortable(),
            Column::make(__('Departamento'), 'department')->searchable()->sortable(),
            Column::make(__('País'), 'country_code')->searchable()->sortable(),
            Column::make(__('Activo'), 'is_active')->format(fn ($v) => $v ? __('Si') : __('No'))->sortable(),
            $this->actionsColumn(),
        ];
    }

    public function deleteModel()
    {
        try {
            $this->selectedModel->delete();
            $this->showSuccessAlert(__('Zona eliminada correctamente'));
        } catch (Exception $error) {
            $this->showErrorAlert($error->getMessage() ?? __('No se pudo eliminar la zona'));
        }
    }
}
