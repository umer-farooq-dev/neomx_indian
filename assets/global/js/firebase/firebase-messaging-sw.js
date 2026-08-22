/*
  Background message handler.

  Only Firebase Messaging is available inside a service worker; the other
  Firebase libraries are not. The SDK here is v12, where the old
  setBackgroundMessageHandler() was removed in favour of onBackgroundMessage() —
  calling the old one throws and the whole worker fails to register.
*/
importScripts('firebase-app.js');
importScripts('firebase-messaging.js');
importScripts('configs.js');

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    const notification = payload.notification || {};
    const data = payload.data || {};

    return self.registration.showNotification(notification.title || '', {
        body: notification.body || '',
        icon: data.icon || undefined,
        image: notification.image || undefined,
        data: { click_action: data.click_action || '/' },
    });
});

// Tapping the notification should open the page it points at, reusing an
// already-open tab when there is one.
self.addEventListener('notificationclick', function (event) {
    const target = (event.notification.data && event.notification.data.click_action) || '/';
    event.notification.close();

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url === target && 'focus' in client) {
                    return client.focus();
                }
            }
            return self.clients.openWindow(target);
        })
    );
});
