@section('title', __('Festivos AVILL'))
<div>
    <x-baseview title="{{ __('Festivos AVILL') }}" :showNew="true">
        <livewire:tables.avill-holiday-table />
    </x-baseview>
    <div x-data="{ open: @entangle('showCreate') }">
        <x-modal confirmText="{{ __('Guardar') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Nuevo festivo AVILL') }}</p>
            @include('livewire.avill.partials.holiday_form')
        </x-modal>
    </div>
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Actualizar') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Editar festivo AVILL') }}</p>
            @include('livewire.avill.partials.holiday_form')
        </x-modal>
    </div>
</div>
