import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const key = import.meta.env.VITE_PUSHER_APP_KEY;

window.Echo = key ? new Echo({
    broadcaster: 'pusher',
    key,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'eu',
    forceTLS: true,
}) : null;

if (import.meta.hot) {
    import.meta.hot.dispose(() => window.Echo?.disconnect());
}
