<template>
    <div class="spotify-player">
        <div class="controls">
            <form method="POST" action="/updateState">
                <div class="btn-group">
                    <div v-if="!isPlaying">
                        <button class="btn btn-lg" type="button" v-on:click="togglePlayPauseBtn">
                            <font-awesome-icon icon="play-circle"/>
                        </button>
                    </div>
                    <div v-if="isPlaying">
                        <button class="btn btn-lg" type="button" v-on:click="togglePlayPauseBtn">
                            <font-awesome-icon icon="pause-circle"/>
                        </button>
                    </div>
                </div>
                <div class="status-bar">
                </div>
            </form>
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
                isPlaying: false,
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
        },
    }
</script>

<style lang="scss" scoped>

.status-bar {
    height: 10px;
    width: 79%;
    border-radius: 6px;
    background-color: #7f8fa6;
    display: inline-block;
}

</style>