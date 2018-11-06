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
                        Currently Playing Song
                    </div>
                    <form method="POST" action="/updateState">
                        <div class="row justify-content-start form-group">
                            <div class="col-sm-2">
                                <div v-if="!isPlaying">
                                    <button class="play-pause-btn" type="button" v-on:click="togglePlayPauseBtn">
                                        <font-awesome-icon icon="play-circle"/>
                                    </button>
                                </div>
                                <div v-if="isPlaying">
                                    <button class="play-pause-btn" type="button" v-on:click="togglePlayPauseBtn">
                                        <font-awesome-icon icon="pause-circle"/>
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <div class="track-progress">
                                    {{ progress }}
                                </div>
                                <div class="progress-bar">
                                </div>
                                <div class="track-duration">
                                    {{ duration }}
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
            "spotifyId": String
        },

        data() {
            return {
                playlistId: "",
                userState: "idle",
                hasBroadcaster: false,
                isPlaying: false,
                progress: "0:00",
                duration: "0:00",
            }
        },

        methods: {
            // Get the MuSync playlist id from the user's playlists
            init() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.getUserPlaylists(this.spotifyId)
                    .then(function(data) {
                        console.log(data.items[0].id);
                        this.playlistId = data.items[0].id;
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
                spotifyApi.setAccessToken(this.accessToken);
                // Play the room creator's playlist associated with the room
                spotifyApi.play({"context_uri": "spotify:user:" + this.spotifyId + ":playlist:" + this.playlistId}, function(err, data) {
                    if (err) console.error(err);
                    else console.log(data);
                });
                this.isPlaying = true;
            },
            pause() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.pause(function(err, data) {
                    if (err) console.error(err);
                    else console.log(data);
                });
                this.isPlaying = false;
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
        computed: {
            getPlaylistId() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.getUserPlaylists(this.spotifyId)
                    .then(function(data) {

                    })

                return this.playlistId;
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
}

</style>
