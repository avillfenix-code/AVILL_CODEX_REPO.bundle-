<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <x-input title="{{ __('Nombre') }}" name="name" />
    <x-select title="{{ __('Tipo') }}" :options="$areaTypes" name="type" />
    <x-input title="{{ __('Ciudad') }}" name="city" />
    <x-input title="{{ __('Departamento') }}" name="department" />
    <x-input title="{{ __('País') }}" name="country_code" />
    <x-input title="{{ __('Referencia') }}" name="reference" />
</div>
<x-textarea title="{{ __('Poligono mapa JSON') }}" name="map_polygon" h="h-40" />
<x-checkbox title="{{ __('Activo') }}" name="is_active" :defer="true" />
