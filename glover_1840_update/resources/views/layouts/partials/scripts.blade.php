<livewire:scripts />
<x-livewire-alert::scripts />
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src="{{ mix('js/app.js') }}"></script>

<!-- Lazy Load Utility -->
<script src="{{ asset('js/charts.js') }}"></script>
<script src="{{ asset('js/init-alpine.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/loading-overlay.js') }}"></script>

@include('layouts.partials.notification')

{{-- @auth
    @if (canI('recieve-system-notifications'))
        <div id="reverb-config"
            data-key="{{ config('broadcasting.connections.reverb.key') }}"
            data-host="{{ config('broadcasting.connections.reverb.options.host', request()->getHost()) }}"
            data-port="{{ config('broadcasting.connections.reverb.options.port', 80) }}"
            data-scheme="{{ config('broadcasting.connections.reverb.options.scheme', 'http') }}"
            data-csrf="{{ csrf_token() }}"
            style="display:none">
        </div>
        <script src="{{ asset('js/backend-notifications.js') }}"></script>
    @endif
@endauth --}}