# MuSync
Music Synchronization Web Application

A web application that allows users to create/join sessions to listen to synchronized music and chat online.
You and your friends can join a session to share music and chat in real-time!

## Requirements
Users must login with a Spotify Premium account to access all features.

## Features
* Creating/joining private and public sessions with unique room IDs
* Real-time chat box within a session
* One user in each session can become the broadcaster to select music to play
* The music will be synchronized between all users in the same session
* Users can search by track name, artist name, or album name to find tracks and add them to their playlist
* Users can select a genre to display a list of tracks to add to their playlist

## Design Details
* MVC (Model–View–Controller) architecture
* LEMP stack (Linux, NGINX, MySQL, and PHP)
* Laravel, Vue.js, Bootstrap
* OAuth for authentication
* Pusher for real-time chat
* Spotify Web API to fetch music data, and to create the music player

## Screenshot of Music Session
![solarized palette](https://github.com/scc23/musync/blob/master/screenshots/room_page.png)
