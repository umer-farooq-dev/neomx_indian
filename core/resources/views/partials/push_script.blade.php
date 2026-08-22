{{-- The Firebase SDK itself is loaded by the layout, since phone-OTP sign-in
     needs it too. This file only drives the messaging side. --}}
<script>
    "use strict";

    var permission = null;
    var authenticated = '{{ auth()->user() ? true : false }}';
    var pushNotify = @json(gs('pn'));
    var firebaseConfig = @json(gs('firebase_config'));

    function pushNotifyAction() {
        permission = Notification.permission;

        if (!('Notification' in window)) {
            notify('info', 'Push notifications not available in your browser. Try Chromium.')
        } else if (permission === 'denied' || permission == 'default') { //Notice for users dashboard
            $('.notice').append(`
            <div class="alert alert--primary" role="alert">
                <div class="alert__icon"><i class="fas fa-bell"></i></div>
                <p class="alert__message">
                    <span class="fw-bold d-block">@lang('Please Allow / Reset Browser Notification')</span>
                    <small><i>@lang('If you want to get push notification then you have to allow notification from your browser') </i></small>
                </p>
            </div>
            `);
        }
    }

    //If enable push notification from admin panel
    if (pushNotify == 1) {
        pushNotifyAction();
    }

    //When users allow browser notification
    if (permission != 'denied' && firebaseConfig) {

        //Firebase
        // the OTP modal may have initialised the app already
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const messaging = firebase.messaging();

        // SDK v12 removed useServiceWorker(), requestPermission() and the
        // no-argument getToken(); the registration and the Web Push key are
        // passed straight to getToken() instead.
        navigator.serviceWorker.register("{{ asset('assets/global/js/firebase/firebase-messaging-sw.js') }}")
            .then((registration) => {

                function registerDeviceToken() {
                    Notification.requestPermission().then(function(result) {
                        if (result !== 'granted') {
                            return;
                        }

                        var options = { serviceWorkerRegistration: registration };
                        if (firebaseConfig.vapidKey) {
                            options.vapidKey = firebaseConfig.vapidKey;
                        }

                        messaging.getToken(options).then(function(token) {
                            if (!token) {
                                console.warn('Push: Firebase returned no device token.');
                                return;
                            }
                            $.post('{{ route('user.add.device.token') }}', {
                                token: token,
                                _token: "{{ csrf_token() }}"
                            }).fail(function(xhr) {
                                console.error('Push: could not save the device token.', xhr.status);
                            });
                        }).catch(function(error) {
                            // silence here is what hid the real problem before
                            console.error('Push: could not get a device token.', error && error.message ? error.message : error);
                        });
                    });
                }

                messaging.onMessage(function(payload) {
                    var notification = payload.notification || {};
                    var data = payload.data || {};
                    new Notification(notification.title || '', {
                        body: notification.body || '',
                        icon: data.icon,
                        image: notification.image,
                        vibrate: [200, 100, 200]
                    });
                });

                //For authenticated users
                if (authenticated) {
                    registerDeviceToken();
                }
            })
            .catch(function(error) {
                console.error('Push: service worker registration failed.', error && error.message ? error.message : error);
            });

    }
</script>
