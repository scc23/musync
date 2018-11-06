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
                <div class="col-12 col-sm-8">
                    <!-- Insert Spotify Player UI -->
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
            "csrfToken": String
        },

        data() {
            return {
                userState: "idle",
                hasBroadcaster: false,
                deviceId: "",
                isPlaying: false,
                progress: "0:00",
                duration: "0:00",
            }
        },

        methods: {
            togglePlayPauseBtn() {
                if (!this.isPlaying) {
                    console.log("Play button is pressed");
                    this.play();
                    return;
                }
                console.log("Pause button is pressed");
                this.pause();
            },
            play() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.play({"uris": ["spotify:track:56y9Vdi0PWuDqjz6RQ2K93"]}, function(err, data) {
                    if (err) console.error(err);
                    else console.log(data);
                });
                this.isPlaying = true;
            //     // Send request to server
            //     $.ajax({
            //         type: "POST",
            //         url: "/updateState",
            //         data: {_token: this.csrfToken},
            //         success:function() {
            //             console.log("data sent to server!");
            //         },
            //         error:function(data, textStatus, errorThrown) {
            //             console.log(data);
            //         }
            //     });
            },
            pause() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.pause(function(err, data) {
                    if (err) console.error(err);
                    else console.log(data);
                });
                this.isPlaying = false;
            },
            getUserData() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.getMyDevices()
                    .then(function(data) {
                        console.log(data);
                        this.deviceId = data["devices"][0]["id"];
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    });
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
    }
</script>

<style lang="scss" scoped>

.spotify-player {
    width: 100%;
    height: 21%;
    border-style: solid;
}
.spotify-player .bottom-controls {
    position: relative;
    width: 100%;
    height: 35%;
    top: 83px;
}
.spotify-player .bottom-controls .play-pause-btn {

}
.spotify-player .bottom-controls .progress-bar {
    position: relative;
    height: 10px;
    width: 60%;
    left: 40px;
    border-radius: 6px;
    display: inline-block;
}
.spotify-player .bottom-controls .track-progress {
    position: absolute;
    left: 60px;
    bottom: 10px;
    text-align: center;
}
.spotify-player .bottom-controls .track-duration {
    position: absolute;
    left: 300px;
    bottom: 10px;
    text-align: center;
}

</style>
