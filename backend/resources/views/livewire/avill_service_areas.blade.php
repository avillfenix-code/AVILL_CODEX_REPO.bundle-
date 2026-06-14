@section('title', __('Zonas AVILL'))
<div>

    <x-baseview title="{{ __('Zonas de Servicio — AVILL Quibdó') }}" :showNew="true">
        <livewire:tables.avill-service-area-table />
    </x-baseview>

    {{-- Modal Crear --}}
    <x-modal :clickAway="false" confirmText="{{ __('Guardar') }}" action="save">
        <p class="text-xl font-semibold">{{ __('Nueva Zona') }}</p>

        <x-input title="{{ __('Nombre de la zona') }}"   name="name" />
        <x-select title="{{ __('Tipo') }}" :options="$zoneTypes" name="type" />
        <x-input title="{{ __('Ciudad') }}"        name="city" />
        <x-input title="{{ __('Departamento') }}"  name="department" />
        <x-input title="{{ __('País (código)') }}" name="country_code" hint="Ej: CO" />
        <x-textarea title="{{ __('Polígono GeoJSON (opcional)') }}" name="map_polygon"
            hint="{{ __('Pegar aquí el JSON del polígono para detección automática de zona') }}" />
        <x-checkbox title="{{ __('Activo') }}" name="isActive" />
    </x-modal>

    {{-- Modal Editar --}}
    <x-modal :clickAway="false" confirmText="{{ __('Actualizar') }}" action="update">
        <p class="text-xl font-semibold">{{ __('Editar Zona') }}</p>

        <x-input title="{{ __('Nombre de la zona') }}"   name="name" />
        <x-select title="{{ __('Tipo') }}" :options="$zoneTypes" name="type" />
        <x-input title="{{ __('Ciudad') }}"        name="city" />
        <x-input title="{{ __('Departamento') }}"  name="department" />
        <x-input title="{{ __('País (código)') }}" name="country_code" hint="Ej: CO" />
        <x-textarea title="{{ __('Polígono GeoJSON (opcional)') }}" name="map_polygon"
            hint="{{ __('Pegar aquí el JSON del polígono para detección automática de zona') }}" />
        <x-checkbox title="{{ __('Activo') }}" name="isActive" />
    </x-modal>

</div>
