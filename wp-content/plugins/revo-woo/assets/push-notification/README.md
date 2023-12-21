# Web-Push-Notifications
This project deals with website push notifications using FCM(Firebase Cloud Messaging).
# Getting Started
1. Create an account in Firebase for Push Notifications. https://firebase.google.com/

2. Goto console and create a project in the Firebase console.

3. Now click on your project you will be directed to a dashboard where you will find Authentication, Database, Storage etc menus on the left panel.

4. Click on "Settings" icons located beside "Overview" on the top of the left menu and select "Project Settings". There you will find tabs like "Cloud messaging, analytics" etc as well as your project ID, web api key which would be required in your project.

5. Create a project on your system. Import all the HTML, JS and other components.

6. Replace FIREBASE-SERVER-KEY, PROJECT-ID & SENDER-ID in "Receiver.html" file with your project ID, sender ID & Firebase API Key obtained from your firebase project console.

7. Run the "receiver.html" & "sender.html" files in the browser.

8. Token ID will be generated in the "receiver.html" file, Use that token in the "sender.html" dashboard for sending the push notifications to the subscribers.

Note: FCM is only supported in localhost:// & domains having SSL certificates(https://).
