@section('title', __('Recargos AVILL'))
<div>

    <x-baseview title="{{ __('Recargos — AVILL') }}" :showNew="true">
        <livewire:tables.avill-surcharge-table />
    </x-baseview>

    {{-- Modal Crear --}}
    <x-modal :clickAway="false" confirmText="{{ __('Guardar') }}" action="save">
        <p class="text-xl font-semibold">{{ __('Nuevo Recargo') }}</p>

        <x-input  title="{{ __('Nombre del recargo') }}"     name="name" />
        <x-select title="{{ __('Servicio (opcional)') }}"    :options="$serviceTypes" name="service_type" />
        <x-input  title="{{ __('Modo vehículo (opcional)') }}" name="vehicle_mode"
            hint="{{ __('Dejar vacío para aplicar a todos') }}" />
        <x-input  title="{{ __('Valor del recargo (COP)') }}" name="amount" type="number" step="100" />
        <x-checkbox title="{{ __('Aplica nocturno (22:00–06:00)') }}" name="applies_night" />
        <x-checkbox title="{{ __('Aplica domingos') }}"   name="applies_sunday" />
        <x-checkbox title="{{ __('Aplica festivos') }}"   name="applies_holiday" />
        <x-checkbox title="{{ __('Activo') }}"            name="isActive" />
    </x-modal>

    {{-- Modal Editar --}}
    <x-modal :clickAway="false" confirmText="{{ __('Actualizar') }}" action="update">
        <p class="text-xl font-semibold">{{ __('Editar Recargo') }}</p>

        <x-input  title="{{ __('Nombre del recargo') }}"     name="name" />
        <x-select title="{{ __('Servicio (opcional)') }}"    :options="$serviceTypes" name="service_type" />
        <x-input  title="{{ __('Modo vehículo (opcional)') }}" name="vehicle_mode"
            hint="{{ __('Dejar vacío para aplicar a todos') }}" />
        <x-input  title="{{ __('Valor del recargo (COP)') }}" name="amount" type="number" step="100" />
        <x-checkbox title="{{ __('Aplica nocturno (22:00–06:00)') }}" name="applies_night" />
        <x-checkbox title="{{ __('Aplica domingos') }}"   name="applies_sunday" />
        <x-checkbox title="{{ __('Aplica festivos') }}"   name="applies_holiday" />
        <x-checkbox title="{{ __('Activo') }}"            name="isActive" />
    </x-modal>

</div>
