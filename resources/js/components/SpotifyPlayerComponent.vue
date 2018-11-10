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
                                <span><img width="50" height="50" v-bind:src="currentTrack.albumArt"/></span>
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
                                            <font-awesome-icon icon="play-circle"/>
                                        </div>
                                        <div v-if="!isPaused">
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
                                    {{ durationMsToMinSec }}
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
            "spotifyDeviceId": String,
            "spotifyPlayerState": Object,
            "playlistId": String,
            "roomId": String,
            "hasBroadcaster": Boolean
        },

        data() {
            return {
                userState: "idle",
                isPaused: true,
                isPlaying: false,
                progress: "0:00",
                currentTrack: {name: "", artists: "", duration: 0, albumArt: ""},
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
                // Play the room creator's playlist associated with the room
                spotifyApi.play({
                    "device_id": this.spotifyDeviceId,
                    "context_uri": "spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId})
                    .then(function() {
                    })
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            pause() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.pause({
                    "device_id": this.spotifyDeviceId})
                    .then(function() {
                    })
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            nextTrack() {
                spotifyApi.setAccessToken(this.accessToken);
                console.log("step forward is pressed");
                spotifyApi.skipToNext({
                    "device_id": this.spotifyDeviceId})
                    .then(function() {
                    })
                    .catch(function(error) {
                        console.error(error);
                    })
            },
            beginBroadcasting() {
                axios.post('/api/room/' + this.roomId + '/broadcast')
                .then((res) => {
                    this.userState = "broadcasting";
                })
            },
            stopBroadcasting() {
                axios.delete('/api/room/' + this.roomId + '/broadcast')
                .then((res) => {
                    this.userState = "idle";
                })
            },
            beginListening() {
                this.userState = "listening";
            },
            stopListening() {
                this.userState = "idle";
            }
        },
        watch: {
            "spotifyPlayerState": function(newState, oldState) {
                this.spotifyPlayerState = newState;
                this.isPaused = this.spotifyPlayerState["paused"];
                this.currentTrack["name"] = this.spotifyPlayerState["track_window"]["current_track"]["name"];
                this.currentTrack["artists"] = this.spotifyPlayerState["track_window"]["current_track"]["artists"][0]["name"];
                this.currentTrack["duration"] = this.spotifyPlayerState["track_window"]["current_track"]["duration_ms"];
                this.currentTrack["albumArt"] = this.spotifyPlayerState["track_window"]["current_track"]["album"]["images"][0]["url"];
            }
        },
        computed: {
            durationMsToMinSec: function() {
                var minutes = (this.currentTrack["duration"] / 1000) / 60;
                var seconds = (this.currentTrack["duration"] / 1000) % 60;
                // return format is "x:xx"
                return Math.floor(minutes) + ":" + String("0" + (Math.floor(seconds))).slice(-2);
            }
        }
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
.track-name {
    font-weight: bold;
}

</style>
