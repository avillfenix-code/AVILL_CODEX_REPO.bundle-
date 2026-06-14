@section('title', __('Tarifas Quibdó'))
<div x-data="{ open: @entangle('showCreate') }">
    <x-baseview title="{{ __('Tarifas Quibdó') }}" :showNew="false">
        <x-slot name="newBtn">
            <button type="button"
                class="flex items-center p-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 border border-transparent rounded-lg bg-primary-600 active:bg-red-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-red"
                x-on:click="open = true" wire:click="showCreateModal">
                <x-heroicon-o-plus class="w-5 h-5 mr-1" />
                {{ __('Nueva tarifa') }}
            </button>
        </x-slot>
        <livewire:tables.avill-manual-fare-table />
    </x-baseview>
    <div>
        <x-modal confirmText="{{ __('Guardar') }}" action="save" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Nueva tarifa Quibdó') }}</p>
            @include('livewire.avill.partials.manual_fare_form')
        </x-modal>
    </div>
    <div x-data="{ open: @entangle('showEdit') }">
        <x-modal confirmText="{{ __('Actualizar') }}" action="update" :clickAway="false">
            <p class="text-xl font-semibold">{{ __('Editar tarifa Quibdó') }}</p>
            @include('livewire.avill.partials.manual_fare_form')
        </x-modal>
    </div>
</div>
