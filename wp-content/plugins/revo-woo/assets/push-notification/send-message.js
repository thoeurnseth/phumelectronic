function sendPushMessage() {

    var receiverToken = document.getElementById("push_ReceiverToken").value;
    var key = 'AAAAL4gX-Xo:APA91bEpfkLw0F8ju_11FVw8RYuleoIve9uUP7QvoYJbT-q4kT7wjBxqN_2gBHhTl-4tBwkzc8IETIw3UwhOHS8__QnaN91Slcqg0HWG9dFq0hRRZA0tDWxkUnvdb-VIVmU9_KIBrcYI '; // Server API key

    var endUsersList = [];
    endUsersList.push(receiverToken);

    var title = document.getElementById("push_Title");
    var message = document.getElementById("push_Message");

    // Generate Notification Content
    var notification = {
        'title': title.value,
        'body': message.value,
        'icon': 'notification-icon.png',
        'click_action': 'http://www.toyotabharat.com/'
    };


    //This function to sends push notification to users
    for (var i = 0; i <= endUsersList.length - 1; i++)
    {
    fetch('https://fcm.googleapis.com/fcm/send', {
        'method': 'POST',
        'headers': {
            'Authorization': 'key=' + key,
            'Content-Type': 'application/json'
        },
        'body': JSON.stringify({
            'notification': notification,
            'to': endUsersList[i]
        })
    }).then(function (response) {
      //  console.log(response);
    }).catch(function (error) {
       // console.error(error);
    })
}

}