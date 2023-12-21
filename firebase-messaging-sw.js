// Scripts for firebase and firebase messaging
importScripts("https://www.gstatic.com/firebasejs/8.2.0/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.2.0/firebase-messaging.js");

// Initialize the Firebase app in the service worker by passing the generated config
const firebaseConfig = {
  apiKey: "AIzaSyDoBpaRVGNZ1RdJalElOA9qqZeWxUYKGvA",
  authDomain: "pe-staging-335009.firebaseapp.com",
  projectId: "pe-staging-335009",
  storageBucket: "pe-staging-335009.appspot.com",
  messagingSenderId: "197417615314",
  appId: "1:197417615314:web:bda8ac92650ef5d66c920b",
  measurementId: "G-9J13K7YFVS"
};

firebase.initializeApp(firebaseConfig);

// Retrieve firebase messaging
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log("Received background message ", payload);
  const notificationTitle = payload.data.title;
  const notificationOptions = {
    body: payload.data.body
  };
  self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', function(event) {
     console.log('event = ',event);
     event.notification.close();
     event.waitUntil(clients.openWindow('https://staging.phumelectronic.com/wp-admin/edit.php?post_type=shop_order'));
 }, false);
