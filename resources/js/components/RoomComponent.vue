<template>
    <div class="container">
        <div class="row justify-content-center">
            <notification-component v-bind:broadcast-notification-text="broadcastNotificationText">
            </notification-component>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <span>{{roomName}}</span>:<span>{{roomId}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <search-tracks-component v-bind:search-results="searchResults"
                                                         @generateResults="generateSearchResults"
                                                         @addTrack="addTrackToPlaylist"
                                                         @refreshToken="refreshAccessToken">
                                </search-tracks-component>
                                <genre-list-component @generateResults="generateSearchResults"
                                                      @addTrack="addTrackToPlaylist"
                                                      @refreshToken="refreshAccessToken">
                                </genre-list-component>
                            </div>
                            <div class="col-4">
                                <spotify-player-component v-bind:csrf-token="csrfToken"
                                                          v-bind:spotify-id="spotifyId"
                                                          v-bind:spotify-device-id="spotifyDeviceId"
                                                          v-bind:spotify-player-state="spotifyPlayerState"
                                                          v-bind:playlist-id="playlistId"
                                                          v-bind:room-id="roomId"
                                                          v-bind:has-broadcaster="hasBroadcaster"
                                                          v-on:become-broadcaster="becomeBroadcaster"
                                                          v-on:stop-being-broadcaster="stopBeingBroadcaster"
                                                          v-on:disconnect-session="disconnectSession"
                                                          v-bind:track-to-play="trackToPlay"
                                                          v-bind:playlist-tracks="playlistTracks"
                                                          v-bind:user-state="userState"
                                                          v-on:set-user-state="setUserState"
                                                          @refreshToken="refreshAccessToken">
                                </spotify-player-component>
                                <playlist-component v-bind:spotify-id="spotifyId"
                                                    v-bind:spotify-player-state="spotifyPlayerState"
                                                    v-bind:track-to-play="trackToPlay"
                                                    v-bind:playlist-tracks="playlistTracks"
                                                    v-bind:user-state="userState"
                                                    @getTrack="getTrackToPlay"
                                                    @change="clearPlaylist"
                                                    @removeTrack="removeTrackFromPlaylist">
                                </playlist-component>
                            </div>
                            <div class="col-4">
                                <user-list-component v-bind:room-id="roomId">
                                </user-list-component>
                                <chat-component v-bind:room-id="roomId">
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
            "notification-component": require("./NotificationComponent.vue"),
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
                "spotifyPlayerState": null,
                "playlistId": "",
                "hasBroadcaster": null,
                "broadcastNotificationText": "",
                "trackToPlay": undefined,
                "playlistTracks": [],
                "userState": "idle",
                "searchResults": []
            };
        },
        created() {
            this.currentAccessToken = this.accessToken;
            this.currentSpotifyId = this.spotifyId;
            this.setAccessToken(this.currentAccessToken);
            this.initializePlaylist(this.currentSpotifyId);
            this.initializeEventListeners();
            this.initializeSpotifyPlayer(this.currentAccessToken);
            this.getRoomBroadcasterStatus();
            window.addEventListener("beforeunload", this.abruptlyCloseSession);
        },
        methods: {
            refreshAccessToken() {
                axios.post("/api/token/refresh")
                .then((res) => {
                    // Call Spotify API to set the new access token
                    spotifyApi.setAccessToken(res.data.api_token);
                    // Set the new access token in this component and it's child components
                    this.setAccessToken(res.data.api_token);
                    console.log("Access token refreshed.");
                })
                .catch((err) => {
                    console.error(err);
                    return false;
                });
                return true;
            },
            initializePlaylist(id) {
                spotifyApi.getUserPlaylists(id)
                    .then(function(data) {
                        // Find MuSync playlist
                        for (var i = 0; i < data.items.length; i++) {
                            if (data.items[i].name == "MuSync") {
                                this.playlistId = data.items[i].id;
                            }
                        }
                    }.bind(this))
                    .then(function(data) {
                        // Get the tracks in the MuSync playlist
                        spotifyApi.getPlaylistTracks(this.playlistId)
                            .then(function(data) {
                                // Store the tracks in playlistTracks
                                for (var i = 0; i < data.items.length; i++) {
                                    this.playlistTracks.push({
                                        trackName: data.items[i].track.name,
                                        trackArtist: data.items[i].track.artists[0].name,
                                        trackAlbumArt: data.items[i].track.album.images[0].url,
                                        trackDuration: data.items[i].track.duration_ms,
                                        trackUri: data.items[i].track.uri
                                    });
                                }
                            }.bind(this))
                            .catch(function(error) {
                                console.error(error);
                            })
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        console.log("Response status: " + error.status);
                        // If the response is 401 Unauthorized Error, refresh the access token
                        if (error.status === 401) {
                            this.refreshAccessToken();
                        }
                    }.bind(this));
            },
            initializeEventListeners() {
                Echo.private(`room.${this.roomId}`)
                    .listen("BroadcasterConnected", (data) => {
                        this.hasBroadcaster = true;
                        this.broadcastNotificationText = data.user.name + " is broadcasting.";
                    })
                    .listen("BroadcasterDisconnected", (data) => {
                        this.hasBroadcaster = false;
                        this.disconnectSession(false);
                        this.userState = "idle";
                        this.broadcastNotificationText = data.user.name + " stopped broadcasting.";
                    })
                    .listen("PlaybackSent", (data) => {
                        this.syncPlayerState(data);
                    });
            },
            becomeBroadcaster(){
                this.broadcastNotificationText = "You are broadcasting.";
            },
            stopBeingBroadcaster(){
                this.broadcastNotificationText = "You stopped broadcasting.";
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
            disconnectSession(isBroadcaster) {
                spotifyApi.pause(function(err, data) {
                    if (err) {
                        console.error("Could not pause playback: " + err);
                        // If the response is 401 Unauthorized Error, refresh the access token
                        if (err.status === 401) {
                            this.refreshAccessToken();
                        }
                    }
                }.bind(this));
                if (isBroadcaster) {
                    axios.delete('/api/room/' + this.roomId + '/broadcast');
                    this.hasBroadcaster = false;
                }
            },
            abruptlyCloseSession() {
                spotifyApi.pause();
                axios.delete('/api/room/' + this.roomId + '/broadcast');
            },
            getTrackToPlay(newData) {
                this.trackToPlay = newData;
                console.log("Track to play: " + this.trackToPlay);
            },
            clearPlaylist() {
                // Get the track uris from playlistTracks
                var tracks = [];
                for (var i = 0; i < this.playlistTracks.length; i++) {
                    tracks.push(this.playlistTracks[i].trackUri);
                }
                // Remove all the tracks from the MuSync playlist
                spotifyApi.removeTracksFromPlaylist(this.playlistId, tracks)
                    .then(function(data) {
                        console.log("Playlist cleared.");
                        this.playlistTracks = [];
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        // If the response is 401 Unauthorized Error, refresh the access token
                        if (error.status === 401) {
                            this.refreshAccessToken();
                        }
                    }.bind(this));
                this.playlistTracks = [];
            },
            removeTrackFromPlaylist(index, uri) {
                console.log("Playlist id: " + this.playlistId);
                console.log("Track uri: " + uri);
                spotifyApi.removeTracksFromPlaylist(this.playlistId, [uri])
                    .then(function(data) {
                        console.log("Track removed from playlist.");
                        this.playlistTracks.splice(index, 1);
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        // If the response is 401 Unauthorized Error, refresh the access token
                        if (error.status === 401) {
                            this.refreshAccessToken();
                        }
                    }.bind(this));
            },
            addTrackToPlaylist(track) {
                console.log(track);
                spotifyApi.addTracksToPlaylist(this.playlistId, [track.trackUri])
                    .then(function(data) {
                        this.playlistTracks.push(track);
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                        if (error.status === 401) {
                            this.refreshAccessToken();
                        }
                    }.bind(this));
            },
            generateSearchResults(results) {
                this.searchResults = [];
                // Populate searchResults with track data
                for (var i = 0; i < results.length; i++) {
                    this.searchResults.push({
                        trackName: results[i].name,
                        trackArtist: results[i].artists[0].name,
                        trackAlbumArt: results[i].album.images[0].url,
                        trackDuration: results[i].duration_ms,
                        trackUri: results[i].uri
                    });
                }
            },
            setUserState(userState) {
                this.userState = userState;
            },
            syncPlayerState(playback) {
                if (this.userState == "listening") {
                    if (playback["isPaused"]) {
                        spotifyApi. pause(function(err, data) {
                            if (err) {
                                console.error("Could not pause playback: " + err);
                                // If the response is 401 Unauthorized Error, refresh the access token
                                if (err.status === 401) {
                                    this.refreshAccessToken();
                                }
                            }
                        }.bind(this));
                    } else {

                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>
