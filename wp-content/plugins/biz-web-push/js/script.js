const config = {
    apiKey: "AIzaSyDoBpaRVGNZ1RdJalElOA9qqZeWxUYKGvA",
    authDomain: "pe-staging-335009.firebaseapp.com",
    projectId: "pe-staging-335009",
    storageBucket: "pe-staging-335009.appspot.com",
    messagingSenderId: "197417615314",
    appId: "1:197417615314:web:bda8ac92650ef5d66c920b",
    measurementId: "G-9J13K7YFVS"
  };
firebase.initializeApp(config);

const messaging = firebase.messaging();
messaging
.requestPermission()
.then(function () {
    
    console.log("Notification permission granted.");

    // get the token in the form of promise
    return messaging.getToken()
})
.then(function(token) {
    console.log('token', token);

    jQuery.post(
        '/wp-admin/admin-ajax.php', 
        {
            'action': 'register_web_token',
            'token':   token
        }, 
        function(response) {
            console.log('The server responded: ', response);
        }
    );

})
.catch(function (err) {
    console.log("Unable to get permission to notify.", err);
});
