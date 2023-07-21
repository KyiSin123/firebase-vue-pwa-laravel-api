importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyBptHdjnsvsUq1abqNRGi2ZsWEFJ92Psv8",
    authDomain: "vue-pwa-notification-f310e.firebaseapp.com",
    projectId: "vue-pwa-notification-f310e",
    storageBucket: "vue-pwa-notification-f310e.appspot.com",
    messagingSenderId: "27925344784",
    appId: "1:27925344784:web:42ed37a562ed5faf435095",
    measurementId: "G-35KVWCV9DC"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    console.log(
        'Received background message',
        payload
    );
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: './img/message-icon.png',
    }
});
