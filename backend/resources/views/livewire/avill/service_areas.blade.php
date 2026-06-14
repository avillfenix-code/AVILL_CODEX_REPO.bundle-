@section('title', __('Zonas AVILL'))
<div>
    <x-baseview title="{{ __('Zonas AVILL') }}" :showNew="true">
        <livewire:tables.avill-service-area-table />
    </x-baseview>
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Guardar') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Nueva zona AVILL') }}</p>
            @include('livewire.avill.partials.service_area_form')
        </x-modal>
    </div>
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Actualizar') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Editar zona AVILL') }}</p>
            @include('livewire.avill.partials.service_area_form')
        </x-modal>
    </div>
</div>
