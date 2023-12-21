
                //Initialise firebase messaging
                const messaging = firebase.messaging();

const tokenDivId = 'divToken';
const permissionDivId = 'permission_div';


messaging.onTokenRefresh(function () {
    messaging.getToken()
    .then(function (refreshedToken) {
        console.log('Token refreshed.');

        setTokenSentToServer(false);
        // Send Token ID token to server.
        sendTokenToServer(refreshedToken);

        // Display new Token ID token.
        resetUserInterface();

    })
    .catch(function (err) {
        console.log('Unable to retrieve refreshed token ', err);
        showToken('Unable to retrieve refreshed token ', err);
    });
});
messaging.onMessage(function (payload) {
    appendMessage(payload);
});
function resetUserInterface() {
    clearMessages();
    showToken('Loading the token...');
    messaging.getToken()
      .then(function (currentToken) {
          if (currentToken) {
              sendTokenToServer(currentToken);
              updateUIForPushEnabled(currentToken);
          } else {
              console.log('Request permission to generate Token.');
              updateUIForPushPermissionRequired();
              setTokenSentToServer(false);
          }
      })
     .catch(function (err) {
         console.log('Error occurred while retrieving token. ', err);
         showToken('Error retrieving Instance ID token. ', err);
         setTokenSentToServer(false);
     });
}
function showToken(currentToken) {
    var tokenElement = document.querySelector('#token');
    tokenElement.textContent = currentToken;
}
function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer()) {
        console.log('Sending token to the server...');
        setTokenSentToServer(true);
    } else {
        console.log('Token sent to server!');
    }
}
function isTokenSentToServer() {
    if (window.localStorage.getItem('sentToServer') == 1) {
        return true;
    }
    return false;
}
function setTokenSentToServer(sent) {
    window.localStorage.setItem('sentToServer', sent ? 1 : 0);
}
function showHideDiv(divId, show) {
    const div = document.querySelector('#' + divId);
    if (show) {
        div.style = "display: visible";
    } else {
        div.style = "display: none";
    }
}
function requestPermission() {
    console.log('Requesting permission...');
    messaging.requestPermission()
    .then(function () {
        console.log('Notification permission granted.');
        resetUserInterface();
    })
    .catch(function (err) {
        console.log('Unable to get permission for notification.', err);
    });
}
function deleteToken() {
    messaging.getToken()
    .then(function (currentToken) {
        messaging.deleteToken(currentToken)
        .then(function () {
            console.log('Token deleted.');
            setTokenSentToServer(false);
            resetUserInterface();
        })
        .catch(function (err) {
            console.log('Unable to delete token. ', err);
        });
    })
    .catch(function (err) {
        console.log('Error retrieving token. ', err);
        showToken('Error retrieving token. ', err);
    });
}
// Add a message to the messages element.
function appendMessage(payload) {
    const messagesElement = document.querySelector('#messages');
    const dataHeaderELement = document.createElement('h5');
    const dataElement = document.createElement('pre');
    dataElement.style = 'overflow-x:hidden;'
    dataHeaderELement.textContent = 'Notification Received:';
    dataElement.textContent = JSON.stringify(payload, null, 2);
    messagesElement.appendChild(dataHeaderELement);
    messagesElement.appendChild(dataElement);
}
// Clear the messages element of all children.
function clearMessages() {
    const messagesElement = document.querySelector('#messages');
    while (messagesElement.hasChildNodes()) {
        messagesElement.removeChild(messagesElement.lastChild);
    }
}
function updateUIForPushEnabled(currentToken) {
    showHideDiv(tokenDivId, true);
    showHideDiv(permissionDivId, false);
    showToken(currentToken);
}
function updateUIForPushPermissionRequired() {
    showHideDiv(tokenDivId, false);
    showHideDiv(permissionDivId, true);
}
resetUserInterface();
