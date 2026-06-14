/**
 * Backend Real-time Notifications via WebSocket
 * This script connects to Laravel Reverb WebSocket server and listens for model events
 * Permission-based notification system for backend users
 */

(function () {
    'use strict';

    // Configuration
    const TOAST_DURATION = 5000; // Duration to show toast in milliseconds
    const TOAST_POSITION = 'top-center'; // Position of toast notification

    // Toast notification container
    let toastContainer = null;

    /**
     * Initialize toast container
     */
    function initToastContainer() {
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'backend-notification-container';
            toastContainer.className = 'fixed z-50 space-y-2 transform -translate-x-1/2 top-4 left-1/2';
            toastContainer.style.cssText = 'max-width: 500px; width: 90%;';
            document.body.appendChild(toastContainer);
        }
        return toastContainer;
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        const container = initToastContainer();

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `transform transition-all duration-300 ease-in-out opacity-0 translate-y-2
            px-6 py-4 rounded-lg shadow-lg border-l-4 flex items-center justify-between space-x-4
            ${getToastClasses(type)}`;

        // Icon based on type
        const icon = getIcon(type);

        toast.innerHTML = `
            <div class="flex items-center space-x-3 flex-1">
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
            <button type="button" class="flex-shrink-0 inline-flex text-gray-400 hover:text-gray-500
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 rounded">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        `;

        // Add click handler for close button
        const closeButton = toast.querySelector('button');
        closeButton.addEventListener('click', () => {
            removeToast(toast);
        });

        // Add to container
        container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
        }, 10);

        // Auto remove after duration
        setTimeout(() => {
            removeToast(toast);
        }, TOAST_DURATION);
    }

    /**
     * Remove toast with animation
     */
    function removeToast(toast) {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    /**
     * Get toast classes based on type
     */
    function getToastClasses(type) {
        const classes = {
            'success': 'bg-green-50 border-green-500 text-green-800',
            'error': 'bg-red-50 border-red-500 text-red-800',
            'warning': 'bg-yellow-50 border-yellow-500 text-yellow-800',
            'info': 'bg-blue-50 border-blue-500 text-blue-800',
            'created': 'bg-green-50 border-green-500 text-green-800',
            'updated': 'bg-blue-50 border-blue-500 text-blue-800',
            'deleted': 'bg-red-50 border-red-500 text-red-800',
        };
        return classes[type] || classes['info'];
    }

    /**
     * Get icon SVG based on type
     */
    function getIcon(type) {
        const icons = {
            'success': `<svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            'error': `<svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            'warning': `<svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>`,
            'info': `<svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            'created': `<svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>`,
            'updated': `<svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>`,
            'deleted': `<svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>`,
        };
        return icons[type] || icons['info'];
    }

    /**
     * Format notification message
     */
    function formatNotificationMessage(data) {
        const { modelType, action, modelData } = data;
        const modelName = modelData.data.name || modelData.data.code || modelData.data.email || `#${modelData.id}`;

        const actionText = {
            'created': 'created',
            'updated': 'updated',
            'deleted': 'deleted'
        };

        return `${modelType} "${modelName}" has been ${actionText[action]}`;
    }

    /**
     * Initialize Echo from #reverb-config data attributes injected by Blade
     */
    function initEcho() {
        const el = document.getElementById('reverb-config');
        if (!el) {
            console.warn('Backend notifications: #reverb-config element not found.');
            return;
        }

        if (typeof window.Pusher === 'undefined') {
            console.warn('Backend notifications: Pusher not available on window.');
            return;
        }

        if (typeof window.Echo === 'undefined') {
            console.warn('Backend notifications: Echo class not available on window.');
            return;
        }

        const EchoClass = window.Echo;
        const port = parseInt(el.dataset.port, 10) || 80;
        const scheme = el.dataset.scheme || 'http';
        const forceTLS = scheme === 'https' || port === 443;

        window.Echo = new EchoClass({
            broadcaster: 'reverb',
            key: el.dataset.key,
            wsHost: el.dataset.host || window.location.hostname,
            wsPort: port,
            wssPort: port,
            forceTLS: forceTLS,
            enabledTransports: forceTLS ? ['ws', 'wss'] : ['ws'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': el.dataset.csrf,
                },
            },
        });

        console.log('Echo initialized from #reverb-config:', { scheme, port, forceTLS });
    }

    /**
     * Initialize WebSocket connection
     */
    function initWebSocket() {
        initEcho();

        if (typeof window.Echo === 'undefined') {
            console.warn('Laravel Echo is not loaded. WebSocket notifications will not work.');
            return;
        }

        console.log('Connecting to backend notifications channel...');

        // Subscribe to the private backend notifications channel
        window.Echo.private('backend-notifications')
            .listen('.model.notification', (data) => {
                console.log('Received notification:', data);

                // Format and show the notification
                const message = formatNotificationMessage(data);
                showToast(message, data.action);

                // Play notification sound (optional)
                playNotificationSound();
            })
            .error((error) => {
                console.error('WebSocket error:', error);
            });

        console.log('Successfully subscribed to backend notifications channel');
    }

    /**
     * Sound settings (loaded from database)
     */
    let soundSettings = {
        sound_enabled: true,
        sound_file: 'notification-1.mp3',
        sound_volume: 30
    };

    /**
     * Load sound settings from server
     */
    async function loadSoundSettings() {
        try {
            const response = await fetch('api/setting/notification/sound-config');
            if (response.ok) {
                soundSettings = await response.json();
                console.log('Sound settings loaded:', soundSettings);
            }
        } catch (error) {
            console.log('Could not load sound settings, using defaults:', error);
        }
    }

    /**
     * Play notification sound
     */
    function playNotificationSound() {
        // Check if sound is enabled
        if (!soundSettings.sound_enabled || soundSettings.sound_file === 'none') {
            return;
        }

        try {
            const audio = new Audio('/sounds/' + soundSettings.sound_file);
            audio.volume = soundSettings.sound_volume / 100;
            audio.play().catch(err => {
                // User interaction required to play audio
                console.log('Audio play prevented:', err);
            });
        } catch (error) {
            console.log('Notification sound not available:', error);
        }
    }

    /**
     * Initialize on DOM ready
     */
    /*
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            loadSoundSettings();
            initWebSocket();
        });
    } else {
        loadSoundSettings();
        initWebSocket();
    }
    */

    // Export for testing/debugging
    window.BackendNotifications = {
        showToast,
        initWebSocket,
        loadSoundSettings
    };

})();
