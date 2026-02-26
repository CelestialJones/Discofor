// Laravel app.js entry point

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// expose globally for listeners
window.Pusher = Pusher;

// configure Echo using Vite environment variables
// ensure you have VITE_PUSHER_APP_KEY and VITE_PUSHER_APP_CLUSTER in .env
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    },
});

// optional: log connection state changes
window.Echo.connector.pusher.connection.bind('state_change', states => {
    console.log('Pusher state change:', states);
});