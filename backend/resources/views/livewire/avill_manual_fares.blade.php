@section('title', __('Tarifas Quibdó'))
<div>

    <x-baseview title="{{ __('Tarifas Quibdó — AVILL') }}" :showNew="true">
        <livewire:tables.avill-manual-fare-table />
    </x-baseview>

    {{-- Modal Crear --}}
    <x-modal :clickAway="false" confirmText="{{ __('Guardar') }}" action="save">
        <p class="text-xl font-semibold">{{ __('Nueva Tarifa') }}</p>

        <x-select title="{{ __('Zona Origen') }}"       :options="$areas"        name="origin_area_id" />
        <x-select title="{{ __('Zona Destino') }}"      :options="$areas"        name="destination_area_id" />
        <x-select title="{{ __('Tipo de servicio') }}"  :options="$serviceTypes" name="service_type" />
        <x-select title="{{ __('Modo de vehículo') }}"  :options="$vehicleModes" name="vehicle_mode" />
        <x-select title="{{ __('Tipo de tarifa') }}"    :options="$pricingModes" name="pricing_mode" />

        <x-input title="{{ __('Tarifa base (COP)') }}"       name="base_amount"                 type="number" step="100" />
        <x-input title="{{ __('Mínimo (COP)') }}"            name="minimum_amount"              type="number" step="100" />
        <x-input title="{{ __('Recargo gestión (COP)') }}"   name="management_surcharge_amount" type="number" step="100" />
        <x-input title="{{ __('Recargo noche (COP)') }}"     name="night_surcharge_amount"      type="number" step="100" />
        <x-input title="{{ __('Recargo festivo (COP)') }}"   name="holiday_surcharge_amount"    type="number" step="100" />
        <x-input title="{{ __('Recargo lluvia (COP)') }}"    name="rain_surcharge_amount"       type="number" step="100" />
        <x-input title="{{ __('Km adicional (COP)') }}"      name="additional_km_amount"        type="number" step="100" />
        <x-checkbox title="{{ __('Activo') }}" name="isActive" />
    </x-modal>

    {{-- Modal Editar --}}
    <x-modal :clickAway="false" confirmText="{{ __('Actualizar') }}" action="update">
        <p class="text-xl font-semibold">{{ __('Editar Tarifa') }}</p>

        <x-select title="{{ __('Zona Origen') }}"       :options="$areas"        name="origin_area_id" />
        <x-select title="{{ __('Zona Destino') }}"      :options="$areas"        name="destination_area_id" />
        <x-select title="{{ __('Tipo de servicio') }}"  :options="$serviceTypes" name="service_type" />
        <x-select title="{{ __('Modo de vehículo') }}"  :options="$vehicleModes" name="vehicle_mode" />
        <x-select title="{{ __('Tipo de tarifa') }}"    :options="$pricingModes" name="pricing_mode" />

        <x-input title="{{ __('Tarifa base (COP)') }}"       name="base_amount"                 type="number" step="100" />
        <x-input title="{{ __('Mínimo (COP)') }}"            name="minimum_amount"              type="number" step="100" />
        <x-input title="{{ __('Recargo gestión (COP)') }}"   name="management_surcharge_amount" type="number" step="100" />
        <x-input title="{{ __('Recargo noche (COP)') }}"     name="night_surcharge_amount"      type="number" step="100" />
        <x-input title="{{ __('Recargo festivo (COP)') }}"   name="holiday_surcharge_amount"    type="number" step="100" />
        <x-input title="{{ __('Recargo lluvia (COP)') }}"    name="rain_surcharge_amount"       type="number" step="100" />
        <x-input title="{{ __('Km adicional (COP)') }}"      name="additional_km_amount"        type="number" step="100" />
        <x-checkbox title="{{ __('Activo') }}" name="isActive" />
    </x-modal>

</div>
