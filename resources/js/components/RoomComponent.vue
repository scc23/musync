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
                                <!-- <search-tracks-component v-bind:access-token="accessToken"
                                                         v-bind:spotify-id="spotifyId"
                                                         v-bind:playlist-id="playlistId">
                                </search-tracks-component> -->
                                <genre-list-component v-bind:access-token="accessToken"
                                                      v-bind:spotify-id="spotifyId"
                                                      v-bind:playlist-id="playlistId">
                                </genre-list-component>
                            </div>
                            <div class="col-4">
                                <spotify-player-component v-bind:csrf-token="csrfToken"
                                                          v-bind:access-token="accessToken"
                                                          v-bind:spotify-id="spotifyId"
                                                          v-bind:spotify-device-id="spotifyDeviceId"
                                                          v-bind:spotify-player-state="spotifyPlayerState"
                                                          v-bind:playlist-id="playlistId">
                                                          v-bind:room-id="roomId"
                                                          v-bind:has-broadcaster="hasBroadcaster">
                                </spotify-player-component>
                                <playlist-component v-bind:access-token="accessToken"
                                                    v-bind:spotify-id="spotifyId">
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
            "search-tracks-component": require("./SearchTracksComponent"),
            "genre-list-component": require("./GenreListComponent.vue"),
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
                "spotifyPlayerState": {},
                "playlistId": "",
                "hasBroadcaster": false,
                "isBroadcaster": false
            };
        },
        created() {
            this.currentAccessToken = this.accessToken;
            this.currentSpotifyId = this.spotifyId;
            this.setAccessToken(this.currentAccessToken);
            this.initializePlaylistId(this.currentAccessToken, this.currentSpotifyId);
            this.initializeSpotifyPlayer(this.currentAccessToken);
            this.getRoomBroadcasterStatus();
            window.addEventListener("beforeunload", this.disconnectSession);
        },
        methods: {
            initializePlaylistId(token, id) {
                spotifyApi.getUserPlaylists(id)
                    .then(function(data) {
                        // Find MuSync playlist
                        for (var i = 0; i < data.items.length; i++) {
                            if (data.items[i].name == "MuSync") {
                                this.playlistId = data.items[i].id;
                            }
                        }
                    }.bind(this))
                    .catch(function(error) {
                        axios.post("/api/token/refresh")
                        .then((res) => {
                            spotifyApi.setAccessToken(res.data.api_token);
                            axios.defaults.headers.common["Authorization"] = "Bearer " + res.data.api_token;
                            console.log("Access token refreshed.");
                        })
                        .catch((err) => {
                            console.error(err);
                        });
                    });
            },

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
                spotifyApi.setAccessToken(token);
                axios.defaults.headers.common["Authorization"] = "Bearer " + token;
                spotifyApi.setAccessToken(token);
            },
            getRoomBroadcasterStatus() {
                axios.get('/api/room/' + this.roomId + '/broadcast')
                .then((res) => {
                    this.hasBroadcaster = true;
                })
                .catch((err) => {
                    if (err.response.status == 404) {
                        this.hasBroadcaster = false;
                    }
                });
            },
            disconnectSession() {
                axios.delete("/api/room/" + this.roomId + "/broadcast")
                .then((res) => {
                    this.isBroadcaster = false;
                })
                .catch((err) => {
                    console.log("Could not stop broadcasting.");
                });
            }
        },
    }
</script>

<style lang="scss" scoped>
</style>
