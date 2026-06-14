<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <x-input title="{{ __('Fecha') }}" name="date" type="date" />
    <x-input title="{{ __('Nombre') }}" name="name" />
    <x-input title="{{ __('País') }}" name="country_code" />
    <x-input title="{{ __('Departamento') }}" name="department" />
    <x-input title="{{ __('Ciudad') }}" name="city" />
    <x-select title="{{ __('Aplica a') }}" :options="$appliesToOptions" name="applies_to" />
</div>
<x-checkbox title="{{ __('Activo') }}" name="is_active" :defer="true" />
