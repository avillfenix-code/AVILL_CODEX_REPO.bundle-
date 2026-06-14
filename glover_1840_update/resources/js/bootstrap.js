window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.io = require('socket.io-client');

window.Pusher = Pusher;
// window.Echo = Echo; // expose the class so plain scripts can do: new window.Echo({...})

// window.Echo instance is initialized in backend-notifications.js via #reverb-config data attributes
const _reverbPort = parseInt(process.env.MIX_REVERB_PORT, 10) || 80;
const _reverbScheme = process.env.MIX_REVERB_SCHEME || 'http';
const _forceTLS = _reverbScheme === 'https' || _reverbPort === 443;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.MIX_REVERB_APP_KEY,
    wsHost: process.env.MIX_REVERB_HOST || window.location.hostname,
    wsPort: _reverbPort,
    wssPort: _reverbPort,
    forceTLS: _forceTLS,
    enabledTransports: _forceTLS ? ['ws', 'wss'] : ['ws'],
    cluster: process.env.MIX_REVERB_APP_CLUSTER || 'mt1',
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
        },
    },
});