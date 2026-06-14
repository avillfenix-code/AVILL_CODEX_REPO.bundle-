@section('title', __('Recargos AVILL'))
<div>
    <x-baseview title="{{ __('Recargos AVILL') }}" :showNew="true">
        <livewire:tables.avill-surcharge-table />
    </x-baseview>
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Guardar') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Nuevo recargo AVILL') }}</p>
            @include('livewire.avill.partials.surcharge_form')
        </x-modal>
    </div>
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Actualizar') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Editar recargo AVILL') }}</p>
            @include('livewire.avill.partials.surcharge_form')
        </x-modal>
    </div>
</div>
