import './toaster';

if (import.meta.env.VITE_REVERB_ENABLED === 'true') {
    let wasDisconnected = false;

    async function handleConnected() {
        if (wasDisconnected) {
            document.dispatchEvent(new CustomEvent('toaster:clear', {detail: {type: 'error'}}));
            const message = await getTranslation('core::messages.notifications.online', 'Connection to server restored.');
            document.dispatchEvent(new CustomEvent('toaster:received', {
                detail: {
                    message: message,
                    type: 'success',
                    duration: 7000
                }
            }));
            wasDisconnected = false;
        }
    }

    async function handleDisconnected() {
        const message = await getTranslation('core::messages.notifications.offline', 'Connection to server lost. Try refreshing the page.');
        document.dispatchEvent(new CustomEvent('toaster:received', {
            detail: {
                message: message,
                type: 'error',
                duration: 1000 * 60 * 60 * 24 // 1 day
            }
        }));
        wasDisconnected = true;
    }

    window.Echo.connector.pusher.connection.bind('connected', handleConnected);
    window.Echo.connector.pusher.connection.bind('disconnected', handleDisconnected);
    window.Echo.connector.pusher.connection.bind('unavailable', handleDisconnected);
}
