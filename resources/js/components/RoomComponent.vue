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
                                                          v-bind:spotify-device-id="spotifyDeviceId"
                                                          v-bind:spotify-player-state="spotifyPlayerState">
                                </spotify-player-component>
                                <playlist-component v-bind:csrf-token="csrfToken">
                                </playlist-component>
                            </div>
                            <div class="col-4">
                                <user-list-component v-bind:csrf-token="csrfToken">
                                </user-list-component>
                                <chat-component v-bind:csrf-token="csrfToken">
                                </chat-component>
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
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
        components: {
            "search-component": require("./SearchComponent.vue"),
            "spotify-player-component": require("./SpotifyPlayerComponent.vue"),
            "playlist-component": require("./PlaylistComponent.vue"),
            "user-list-component": require("./UserListComponent.vue"),
            "chat-component": require("./ChatComponent.vue"),
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
                "spotifyDeviceId": "",
                "spotifyPlayerState": {}
            };
        },
        created() {
            this.currentAccessToken = this.accessToken;
            this.setAccessToken(this.currentAccessToken);
            this.initializeSpotifyPlayer(this.currentAccessToken);
        },
        methods: {
            initializeSpotifyPlayer(token) {
                window.onSpotifyWebPlaybackSDKReady = () => {
                    const player = new Spotify.Player({
                        name: 'MuSync Web Player',
                        getOAuthToken: cb => { cb(token); }
                    });

                    player.addListener('player_state_changed', state => { 
                        this.spotifyPlayerState = state;
                    });

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
                spotifyApi.setAccessToken(token);
            }
        },
    }
</script>

<style>
</style>
