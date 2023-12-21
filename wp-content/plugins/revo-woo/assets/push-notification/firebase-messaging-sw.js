//Import firebase libraries to the client

importScripts('https://www.gstatic.com/firebasejs/3.5.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.5.0/firebase-messaging.js');

// Firebase cloud messaging sender ID.
firebase.initializeApp({
    'messagingSenderId': '<SENDER-ID>'  //Firebase Project Sender ID ex:204146733640
});

//Initialise firebase messaging
const initMessaging = firebase.messaging();


//Background push notification handler. It fires up when the browser or web page in which push notification is activated are closed.
initMessaging.setBackgroundMessageHandler(function (payload) {
    console.log('Background message Received:', payload);
    const notificationTitle = 'Notification Title';
    const notificationOptions = {
        body: 'Notification Body.',
        icon: 'notification-icon.png'
    };
    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});

//Displays incoming push notification
self.addEventListener('push', function (event) {

  console.log('Push Notification Received.');
  console.log('Push Notification had this data: "${event.data.text()}"');
    
    var eventData = event.data.text();
    var obj = JSON.parse(eventData); //Parse the received JSON object.

    const title = obj.notification.title;
    const options = {
        body: obj.notification.body, 
        icon: 'notification-icon.png',
        badge: 'notification-icon.png',
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

//Take action when a user clicks on the received notification.
self.addEventListener('notificationclick', function (event) {

    event.notification.close();

    event.waitUntil(
      clients.openWindow('https://www.facebook.com/rakeshdadamatti')
    );
});

//Push subscription change
self.addEventListener('pushsubscriptionchange', function (event) {
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    event.waitUntil(
      self.registration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: applicationServerKey
      })
      .then(function (newSubscription) {
          console.log('Push subscription is changed. New Subscription is: ', newSubscription);
      })
    );
});