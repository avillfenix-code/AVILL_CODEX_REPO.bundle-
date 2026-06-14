<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <x-input title="{{ __('Nombre') }}" name="name" />
    <x-input title="{{ __('Valor') }}" name="amount" type="number" step="0.01" />
    <x-select title="{{ __('Servicio') }}" :options="$serviceTypes" name="service_type" />
    <x-select title="{{ __('Vehículo') }}" :options="$vehicleModes" name="vehicle_mode" />
    <x-input title="{{ __('Hora inicio noche') }}" name="night_starts_at" type="time" />
    <x-input title="{{ __('Hora fin noche') }}" name="night_ends_at" type="time" />
    <x-input title="{{ __('Ano de vigencia') }}" name="effective_year" />
    <x-input title="{{ __('Decreto') }}" name="decree_number" />
</div>
<div class="grid grid-cols-1 gap-6 md:grid-cols-4">
    <x-checkbox title="{{ __('Aplica noche') }}" name="applies_night" :defer="true" />
    <x-checkbox title="{{ __('Aplica domingo') }}" name="applies_sunday" :defer="true" />
    <x-checkbox title="{{ __('Aplica festivo') }}" name="applies_holiday" :defer="true" />
    <x-checkbox title="{{ __('Activo') }}" name="is_active" :defer="true" />
</div>
<x-textarea title="{{ __('Notas') }}" name="notes" h="h-24" />
