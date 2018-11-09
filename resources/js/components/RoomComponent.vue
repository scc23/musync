<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <span>{{roomName}}</span>:<span>{{roomId}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <search-component v-bind:access-token="accessToken"
                                                  v-bind:spotify-id="spotifyId">
                                </search-component>
                            </div>
                            <div class="col-4">
                                <spotify-player-component v-bind:csrf-token="csrfToken"
                                                          v-bind:access-token="accessToken"
                                                          v-bind:spotify-id="spotifyId"
                                                          v-bind:spotify-device-id="spotifyDeviceId">
                                </spotify-player-component>
                                <playlist-component v-bind:csrf-token="csrfToken">
                                </playlist-component>
                            </div>
                            <div class="col-4">
                                <!-- <user-list-component csrf-token="csrfToken"
                                                     access-token"accessToken">
                                </user-list-component>
                                <chat-messages-component csrf-token="csrfToken"
                                                         access-token"accessToken">
                                </chat-messages-component>
                                <chat-form-component csrf-token="csrfToken"
                                                     access-token"accessToken">
                                </chat-form-component> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        components: {
            "search-component": require("./SearchComponent.vue"),
            "spotify-player-component": require("./SpotifyPlayerComponent.vue"),
            "playlist-component": require("./PlaylistComponent.vue"),
            "user-list-component": require("./UserListComponent.vue"),
            "chat-messages-component": require("./ChatMessagesComponent.vue"),
            "chat-form-component": require("./ChatFormComponent.vue")
        },
        props: {
            "roomName": String,
            "roomId": String,
            "csrfToken": String,
            "accessToken": String,
            "spotifyId": String
        },
        data() {
            return {
                "currentAccessToken": "",
                "spotifyDeviceId": ""
            };
        },
        created() {
            this.currentAccessToken = this.accessToken;
            this.initializeSpotifyPlayer(this.currentAccessToken);
            this.setAccessToken(this.currentAccessToken);
        },
        methods: {
            initializeSpotifyPlayer(token) {
                window.onSpotifyWebPlaybackSDKReady = () => {
                    const player = new Spotify.Player({
                        name: 'MuSync Web Player',
                        getOAuthToken: cb => { cb(token); }
                    });

                    // Error handling
                    player.addListener('initialization_error', ({ message }) => { console.error(message); });
                    player.addListener('authentication_error', ({ message }) => { console.error(message); });
                    player.addListener('account_error', ({ message }) => { console.error(message); });
                    player.addListener('playback_error', ({ message }) => { console.error(message); });

                    // Playback status updates
                    player.addListener('player_state_changed', state => { console.log(state); });

                    // Ready
                    player.addListener('ready', ({ device_id }) => {
                      console.log('Ready with Device ID', device_id);
                      this.spotifyDeviceId = device_id;
                    });

                    // Not Ready
                    player.addListener('not_ready', ({ device_id }) => {
                      console.log('Device ID has gone offline', device_id);
                    });

                    // Connect to the player!
                    player.connect();
                };
            },
            setAccessToken(token) {
                axios.defaults.headers.common["Authorization"] = "Bearer " + token;
            }
        }
    }
</script>

<style>
</style>
