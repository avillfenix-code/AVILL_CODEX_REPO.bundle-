<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <x-select title="{{ __('Servicio') }}" :options="$serviceTypes" name="service_type" />
    <x-select title="{{ __('Vehículo') }}" :options="$vehicleModes" name="vehicle_mode" />
    <x-select title="{{ __('Barrio origen') }}" :options="$areas" name="origin_area_id" />
    <x-select title="{{ __('Barrio destino') }}" :options="$areas" name="destination_area_id" />
    <x-input title="{{ __('Ciudad origen') }}" name="origin_city" />
    <x-input title="{{ __('Ciudad destino') }}" name="destination_city" />
    <x-select title="{{ __('Modo de cobro') }}" :options="$pricingModes" name="pricing_mode" />
    <x-input title="{{ __('Tarifa fija') }}" name="base_amount" type="number" step="0.01" />
    <x-input title="{{ __('Recargo gestión') }}" name="management_surcharge_amount" type="number" step="0.01" />
    <x-input title="{{ __('Tarifa minima') }}" name="minimum_amount" type="number" step="0.01" />
    <x-input title="{{ __('Precio km adicional') }}" name="additional_km_amount" type="number" step="0.01" />
    <x-input title="{{ __('Recargo nocturno') }}" name="night_surcharge_amount" type="number" step="0.01" />
    <x-input title="{{ __('Recargo festivo') }}" name="holiday_surcharge_amount" type="number" step="0.01" />
    <x-input title="{{ __('Recargo lluvia') }}" name="rain_surcharge_amount" type="number" step="0.01" />
    <x-input title="{{ __('Valor por pasajero') }}" name="per_passenger_amount" type="number" step="0.01" />
    <x-input title="{{ __('Cupos disponibles') }}" name="available_seats" type="number" />
    <x-input title="{{ __('Ano de vigencia') }}" name="effective_year" />
    <x-input title="{{ __('Decreto') }}" name="decree_number" />
    <x-input title="{{ __('Inicio') }}" name="starts_at" type="date" />
    <x-input title="{{ __('Fin') }}" name="ends_at" type="date" />
</div>
<x-textarea title="{{ __('Nota') }}" name="notes" h="h-24" />
<x-checkbox title="{{ __('Activo') }}" name="is_active" :defer="true" />
