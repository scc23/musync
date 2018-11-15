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
                        <div class="col-sm-4">
                            <div v-if="currentTrack.albumArt != ''">
                                <span><img width="90" height="90" v-bind:src="currentTrack.albumArt"/></span>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <span class="track-name">{{ currentTrack["name"] }}</span><br>
                            <span>{{ currentTrack["artists"] }}</span>
                        </div>
                    </div>
                    <form method="POST" action="/updateState">
                        <div class="row justify-content-start form-group">
                            <div class="col-sm-2">
                                <div class="btn-group">
                                    <button class="play-pause-btn" type="button" v-on:click="togglePlayPauseBtn">
                                        <div v-if="isPaused">
                                            <font-awesome-icon class="player-icons" icon="play-circle"/>
                                        </div>
                                        <div v-if="!isPaused">
                                            <font-awesome-icon class="player-icons" icon="pause-circle"/>
                                        </div>
                                    </button>
                                    <button class="player-icons step-forward-btn" type="button" v-on:click="nextTrack">
                                        <font-awesome-icon icon="step-forward"/>
                                    </button>
                                </div>
                            </div>
                            <div class="col track-container">
                                <div class="track-progress">
                                    {{ msToMinSec(currentTrack["trackPosition"]) }}
                                </div>
                                <div class="track-progress-bar">
                                    <vue-slider
                                        v-model="currentTrack.trackPosition"
                                        v-on:drag-start="onDragStart"
                                        v-on:drag-end="onDragEnd"
                                        v-on:callback="onProgressChange"
                                        :max='currentTrack["duration"]'
                                        :tooltip="false">
                                    </vue-slider>
                                </div>
                                <div class="track-duration">
                                    {{ msToMinSec(currentTrack["duration"]) }}
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
    import vueSlider from 'vue-slider-component'

    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
        components: {
            vueSlider
        },
        props: {
            "csrfToken": String,
            "spotifyId": String,
            "spotifyDeviceId": String,
            "spotifyPlayerState": Object,
            "playlistId": String,
            "roomId": String,
            "hasBroadcaster": Boolean,
            "trackToPlay": Number,
            "playlistTracks": Array,
            "userState": String
        },

        data() {
            return {
                isPaused: true,
                progressInterval: null,
                isDragStart: false,
                currentTrack: {name: "", artists: "", duration: 0, albumArt: "", trackUri: "", trackPosition: 0, trackIndex: 0}
            }
        },

        methods: {
            togglePlayPauseBtn() {
                if (this.isPaused) {
                    console.log("spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId);
                    console.log("Play button is pressed");
                    this.play();
                    return;
                }
                console.log("Pause button is pressed");
                this.pause();
            },
            play() {
                // Play the room creator's playlist associated with the room if current song doesn't exist
                if (this.spotifyPlayerState == null) {
                    spotifyApi.play({
                        "device_id": this.spotifyDeviceId,
                        "context_uri": "spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId})
                        .catch(function(error) {
                            console.error(error);
                            // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                            if (error.status === 401) {
                                $this.emit("refreshToken");
                            }
                        }.bind(this))
                // Play the room's current song
                } else {
                    spotifyApi.play({
                        "device_id": this.spotifyDeviceId,
                        "context_uri": "spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId,
                        "offset": {"position": this.currentTrack["trackIndex"]},
                        "position_ms": this.currentTrack["trackPosition"]})
                        .catch(function(error) {
                            console.error(error);
                            // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                            if (error.status === 401) {
                                $this.emit("refreshToken");
                            }
                        }.bind(this))
                }
            },
            pause() {
                spotifyApi.pause({
                    "device_id": this.spotifyDeviceId})
                    .catch(function(error) {
                        console.error(error);
                        // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                        if (error.status === 401) {
                            $this.emit("refreshToken");
                        }
                    }.bind(this))
            },
            nextTrack() {
                console.log("step forward is pressed");
                spotifyApi.skipToNext({
                    "device_id": this.spotifyDeviceId})
                    .catch(function(error) {
                        console.error(error);
                        // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                        if (error.status === 401) {
                            $this.emit("refreshToken");
                        }
                    }.bind(this))
            },
            seekToPosition(position_ms) {
                spotifyApi.seek(position_ms, {"device_id": this.spotifyDeviceId})
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            updateProgress() {
                clearInterval(this.progressInterval);
                if (!this.isPaused) {
                    this.progressInterval = setInterval(this.incrementProgressTime, 1000)
                }
            },
            incrementProgressTime() {
                this.currentTrack["trackPosition"] = this.currentTrack["trackPosition"] + 1000;
            },
            onDragStart({currentValue}) {
                this.isDragStart = true;
            },
            onDragEnd({currentValue}) {
                this.isDragStart = false;
                this.seekToPosition(currentValue);
            },
            onProgressChange(currentValue) {
                if (!this.isDragStart) {
                    this.isDragStart = false;
                    this.seekToPosition(currentValue);
                }
            },
            setCurrentTrackIndex() {
                for (var i = 0; i < this.playlistTracks.length; i++) {
                    if (this.currentTrack["name"] == this.playlistTracks[i]["trackName"]) {
                        this.currentTrack["trackIndex"] = i;
                    }
                }
            },
            msToMinSec(ms) {
                var minutes = (ms / 1000) / 60;
                var seconds = (ms / 1000) % 60;
                return Math.floor(minutes) + ":" + String("0" + (Math.floor(seconds))).slice(-2);
            },
            beginBroadcasting() {
                axios.post('/api/room/' + this.roomId + '/broadcast')
                .then((res) => {
                    this.$emit("set-user-state", "broadcasting")
                })
            },
            stopBroadcasting() {
                this.$emit("disconnect-session", true);
                this.$emit("set-user-state", "idle")
            },
            beginListening() {
                this.userState = "listening";
                this.$emit("set-user-state", "listening")
            },
            stopListening() {
              this.$emit("disconnect-session", false);
              this.$emit("set-user-state", "idle")
            }
        },
        watch: {
            "spotifyPlayerState": function(newState, oldState) {
                this.spotifyPlayerState = newState;
                if (this.spotifyPlayerState != null) {
                    this.isPaused = this.spotifyPlayerState["paused"];
                    this.currentTrack["name"] = this.spotifyPlayerState["track_window"]["current_track"]["name"];
                    this.currentTrack["artists"] = this.spotifyPlayerState["track_window"]["current_track"]["artists"][0]["name"];
                    this.currentTrack["duration"] = this.spotifyPlayerState["track_window"]["current_track"]["duration_ms"];
                    this.currentTrack["albumArt"] = this.spotifyPlayerState["track_window"]["current_track"]["album"]["images"][0]["url"];
                    this.currentTrack["trackUri"] = this.spotifyPlayerState["track_window"]["current_track"]["uri"];
                    this.currentTrack["trackPosition"] = this.spotifyPlayerState["position"];
                }
            },
            "trackToPlay": function(newState, oldState) {
                this.trackToPlay = newState;
                console.log("trackToPlay updated to " + this.trackToPlay + " in SpotifyPlayerComponent.");
                this.currentTrack["trackIndex"] = this.trackToPlay;
                this.currentTrack["trackPosition"] = 0;
                this.play();
            },
            "playlistTracks": function(newState, oldState) {
                this.playlistTracks = newState;
            },
            "isPaused": function(newValue, oldValue) {
                this.updateProgress();
            },
            "currentTrack.name": function(newValue, oldValue) {
                this.setCurrentTrackIndex();
            }
        }
    }
</script>

<style lang="scss" scoped>

.player-icons {
    opacity: 1;
    transition: opacity .2s ease-out;
    -moz-transition: opacity .2s ease-out;
    -webkit-transition: opacity .2s ease-out;
    -o-transition: opacity .2s ease-out;
}
.player-icons:hover {
    opacity: 0.5;
}
.track-progress-bar {
    width: 71%;
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
.track-name {
    font-weight: bold;
}

</style>
