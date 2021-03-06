<template>
    <div class="container">
        <div class="loading hide">
            <div class="obj"></div>
            <div class="obj"></div>
            <div class="obj"></div>
            <div class="obj"></div>
            <div class="obj"></div>
            <div class="obj"></div>
            <div class="obj"></div>
            <div class="obj"></div>
        </div>
        <div class="row justify-content-center hide">
            <notification-component v-bind:notification-text="notificationText">
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
                                                    v-bind:playlist-tracks="playlistTracks"
                                                    v-bind:user-state="userState"
                                                    @getTrack="getTrackToPlay"
                                                    @change="clearPlaylist"
                                                    @removeTrack="removeTrackFromPlaylist">
                                </playlist-component>
                            </div>
                            <div class="col-4">
                                <user-list-component v-bind:room-id="roomId"
                                                     v-bind:broadcaster-name="broadcasterName"
                                                     v-bind:broadcaster-id="broadcasterId"
                                                     v-on:user-join="userJoin"
                                                     v-on:user-leave="userLeave">
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
            "userName": String,
            "userId": String,
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
                "notificationText": "",
                "broadcasterName": "",
                "broadcasterId": "",
                "trackToPlay": {index: undefined, uri: ""},
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
        computed: {
            readyToLoad: function() {
                if (this.spotifyDeviceId != "" && this.playlistId != "" && this.hasBroadcaster != null) {
                    return true;
                }
                else {
                    return false;
                }
            }
        },
        watch: {
            readyToLoad: function(val) {
                $(document).ready(function() {
                    if (val == true) {
                        $(".loading").delay(1500).fadeOut("fast");
                        $(".hide").delay(1500).fadeIn("fast");
                    }
                    else {
                        $(".loading").delay(1500).fadeIn("fast");
                        $(".hide").delay(1500).fadeOut("fast");
                    }
                });
            }
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
                        this.notificationText = data.user.name + " is broadcasting.";
                        this.broadcasterName = data.user.name;
                        this.broadcasterId = data.user.id.toString();
                    })
                    .listen("BroadcasterDisconnected", (data) => {
                        this.hasBroadcaster = false;
                        this.disconnectSession(false);
                        this.userState = "idle";
                        this.notificationText = data.user.name + " stopped broadcasting.";
                        this.broadcasterName = "";
                        this.broadcasterId = "";
                    })
                    .listen("PlaybackSent", (data) => {
                        this.syncPlayerState(data);
                    });
            },
            becomeBroadcaster(){
                this.notificationText = "You are broadcasting.";
                this.broadcasterName = this.userName;
                this.broadcasterId = this.userId;
            },
            stopBeingBroadcaster(){
                this.notificationText = "You stopped broadcasting.";  
                this.broadcasterName = "";
                this.broadcasterId = "";
            },
            userJoin(userName){
                this.notificationText = userName + " has joined the room.";
            },
            userLeave(userName){
                this.notificationText = userName + " has left the room.";
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
            getTrackToPlay(trackObject) {
                this.trackToPlay = trackObject;
                console.log("track to play index: " + this.trackToPlay["index"]);
                console.log("track to play uri: " + this.trackToPlay["uri"]);
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
                        spotifyApi.pause(function(err, data) {
                            if (err) {
                                console.error("Could not pause playback: " + err);
                                // If the response is 401 Unauthorized Error, refresh the access token
                                if (err.status === 401) {
                                    this.refreshAccessToken();
                                }
                            }
                        }.bind(this));
                    } else {
                        var currentTime = new Date();
                        var rtt = (currentTime - playback["timestamp"]) * 2;
                        spotifyApi.play({
                            "device_id": this.spotifyDeviceId,
                            "uris": [playback["trackUri"]],
                            "position_ms": playback["trackPosition"] + rtt
                        }).catch(function(error) {
                            console.error(error);
                            // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                            if (error.status === 401) {
                                this.$emit("refreshToken");
                            }
                        }.bind(this));
                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
.hide {
    display: none;
}


.loading{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    height: 50px;
    display: flex;
    align-items: center;
}
.obj{
    width: 6px;
    height: 0px;
    background-color: #1DB954;
    margin: 0 3px;
    border-radius: 10px;
    animation: loading 0.8s infinite;
}
.obj:nth-child(2){
    animation-delay: 0.1s;
}
.obj:nth-child(3){
    animation-delay: 0.2s;
}
.obj:nth-child(4){
    animation-delay: 0.3s;
}
.obj:nth-child(5){
    animation-delay: 0.4s;
}
.obj:nth-child(6){
    animation-delay: 0.5s;
}
.obj:nth-child(7){
    animation-delay: 0.6s;
}
.obj:nth-child(8){
    animation-delay: 0.7s;
}

@keyframes loading {
    0% {
        height: 0;
    }
    50% {
        height: 50px;
    }
    100% {
        height: 0;
    }
}
</style>
