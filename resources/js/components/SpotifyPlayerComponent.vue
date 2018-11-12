<template>
    <div class="card">
        <div class="card-body" v-if="userState == 'idle'">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8">
                    <button class="home-btn btn btn-primary btn-block" v-if="hasBroadcaster" @click="beginListening">
                        Listen
                    </button>
                    <button class="home-btn btn btn-primary btn-block" v-else @click="beginBroadcasting">
                        Broadcast
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body" v-else-if="userState == 'broadcasting'">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row justify-content-center">
                        {{ currentTrack["name"] }}
                    </div>
                    <form method="POST" action="/updateState">
                        <div class="row justify-content-start form-group">
                            <div class="col-sm-2">
                                <div class="btn-group">
                                    <button class="play-pause-btn" type="button" v-on:click="togglePlayPauseBtn">
                                        <div v-if="!isPlaying">
                                            <font-awesome-icon icon="play-circle"/>
                                        </div>
                                        <div v-if="isPlaying">
                                            <font-awesome-icon icon="pause-circle"/>
                                        </div>
                                    </button>
                                    <button class="step-forward-btn" type="button" v-on:click="nextTrack">
                                        <font-awesome-icon icon="step-forward"/>
                                    </button>
                                </div>
                            </div>
                            <div class="col track-container">
                                <div class="track-progress">
                                    {{ progress }}
                                </div>
                                <div class="progress-bar">
                                </div>
                                <div class="track-duration">
                                    {{ currentTrack["duration"] }}
                                </div>
                            </div>
                        </div>
                    </form>
                    <button class="home-btn btn btn-primary btn-block" @click="stopBroadcasting">
                        Stop Broadcasting
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body" v-else-if="userState == 'listening'">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8">
                    <button class="home-btn btn btn-primary btn-block" @click="stopListening">
                        Stop Listening
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();
    export default {
        props: {
            "accessToken": String,
            "csrfToken": String,
            "spotifyId": String,
            "spotifyDeviceId": String
        },

        data() {
            return {
                playlistId: "",
                userState: "idle",
                hasBroadcaster: false,
                isPlaying: false,
                progress: "0:00",
                currentTrack: {name: "", artists: "", duration: "0:00"},
            }
        },

        methods: {
            // Get the MuSync playlist id from the user's playlists
            init() {
                spotifyApi.getUserPlaylists(this.spotifyId)
                    .then(function(data) {
                        // Find MuSync playlist
                        for (var i = 0; i < data.items.length; i++) {
                            if (data.items[i].name == "MuSync") {
                                this.playlistId = data.items[i].id;
                            }
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    })
                return this.playlistId;
            },
            togglePlayPauseBtn() {
                if (!this.isPlaying) {
                    console.log("spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId);
                    console.log("Play button is pressed");
                    this.play();
                    return;
                }
                console.log("Pause button is pressed");
                this.pause();
            },
            play() {
                // Play the room creator's playlist associated with the room
                spotifyApi.play({
                    "device_id": this.spotifyDeviceId,
                    "context_uri": "spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId})
                    .then(function() {
                        return spotifyApi.getMyCurrentPlayingTrack();
                    })
                    .then(function(currentPlayingTrack) {
                        this.isPlaying = currentPlayingTrack["is_playing"];
                        console.log(currentPlayingTrack);
                        this.currentTrack["name"] = currentPlayingTrack["item"]["name"]; 
                        //TODO: Get track artist of all the artist. currentPlayingTrack["item"]["artists"] returns an array
                        // this.currentTrack["artists"] = currentPlayingTrack["item"]["artists"][0]["name"];
                        this.currentTrack["duration"] = this.durationMsToMinSec(currentPlayingTrack["item"]["duration_ms"]);
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            pause() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.pause()
                    .then(function() {
                        return spotifyApi.getMyCurrentPlayingTrack();
                    })
                    .then(function(currentPlayingTrack) {
                        this.isPlaying = currentPlayingTrack["is_playing"];
                        console.log(currentPlayingTrack);
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            nextTrack() {
                spotifyApi.setAccessToken(this.accessToken);
                console.log("step forward is pressed");
                spotifyApi.skipToNext()
                    .then(function() {
                        return spotifyApi.getMyCurrentPlayingTrack();
                    })
                    .then(function(currentPlayingTrack) {
                        this.isPlaying = currentPlayingTrack["is_playing"];
                        console.log(currentPlayingTrack);
                        this.currentTrack["name"] = currentPlayingTrack["item"]["name"]; 
                        //TODO: Get track artist of all the artist. currentPlayingTrack["item"]["artists"] returns an array
                        // this.currentTrack["artists"] = currentPlayingTrack["item"]["artists"][0]["name"];
                        this.currentTrack["duration"] = this.durationMsToMinSec(currentPlayingTrack["item"]["duration_ms"]);
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            durationMsToMinSec(ms) {
                var minutes = (ms / 1000) / 60;
                var seconds = (ms / 1000) % 60;
                return Math.floor(minutes) + ":" + Math.floor(seconds);
            },
            beginBroadcasting() {
                this.userState = "broadcasting";
            },
            stopBroadcasting() {
                this.userState = "idle";
            },
            beginListening() {
                this.userState = "listening";
            },
            stopListening() {
                this.userState = "idle";
            }
        },
        mounted() {
            this.init();
        },
    }
</script>

<style lang="scss" scoped>

.progress-bar {
    height: 10px;
    width: 71%;
    border-radius: 6px;
    display: inline-block;
}
.track-progress {
    display: inline-block;
}
.track-duration {
    display: inline-block;
}
.play-pause-btn {
    border: none;
    outline: none;
    cursor: pointer;
}
.step-forward-btn {
    border: none;
    outline: none;
    cursor: pointer;
}
.track-container {
    margin-left: 10px;
}

</style>
