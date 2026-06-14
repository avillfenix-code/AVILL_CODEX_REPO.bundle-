@section('title', __('Festivos AVILL'))
<div>

    <x-baseview title="{{ __('Festivos Colombianos — AVILL') }}" :showNew="true">
        <livewire:tables.avill-holiday-table />
    </x-baseview>

    {{-- Modal Crear --}}
    <x-modal :clickAway="false" confirmText="{{ __('Guardar') }}" action="save">
        <p class="text-xl font-semibold">{{ __('Nuevo Festivo') }}</p>

        <x-input  title="{{ __('Fecha') }}"          name="date"         type="date" />
        <x-input  title="{{ __('Nombre') }}"          name="name" />
        <x-input  title="{{ __('País (código)') }}"   name="country_code" hint="Ej: CO" />
        <x-input  title="{{ __('Departamento') }}"    name="department"
            hint="{{ __('Dejar vacío para festivo nacional') }}" />
        <x-input  title="{{ __('Ciudad') }}"          name="city"
            hint="{{ __('Dejar vacío para festivo departamental o nacional') }}" />
        <x-select title="{{ __('Aplica a') }}"        :options="$appliesToOptions" name="applies_to" />
        <x-checkbox title="{{ __('Activo') }}"        name="isActive" />
    </x-modal>

    {{-- Modal Editar --}}
    <x-modal :clickAway="false" confirmText="{{ __('Actualizar') }}" action="update">
        <p class="text-xl font-semibold">{{ __('Editar Festivo') }}</p>

        <x-input  title="{{ __('Fecha') }}"          name="date"         type="date" />
        <x-input  title="{{ __('Nombre') }}"          name="name" />
        <x-input  title="{{ __('País (código)') }}"   name="country_code" hint="Ej: CO" />
        <x-input  title="{{ __('Departamento') }}"    name="department"
            hint="{{ __('Dejar vacío para festivo nacional') }}" />
        <x-input  title="{{ __('Ciudad') }}"          name="city"
            hint="{{ __('Dejar vacío para festivo departamental o nacional') }}" />
        <x-select title="{{ __('Aplica a') }}"        :options="$appliesToOptions" name="applies_to" />
        <x-checkbox title="{{ __('Activo') }}"        name="isActive" />
    </x-modal>

</div>
