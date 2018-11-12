<template>
    <div class="track-block">
        <div>
            <span class="track-album-art"><img width="70" height="70" v-bind:src="playlistTrack.trackAlbumArt"/></span>
            <span class="track-name">{{playlistTrack.trackName}}</span>
            <button class="remove-button" v-on:click="removeTrack($event)" v-bind:value="playlistTrack.trackUri">Remove</button><br>
            <span class="track-artist">{{playlistTrack.trackArtist}}</span><br>
            <span class="track-duration">{{trackDuration}}</span>
        </div>

        <!-- TODO: Click on a track to play it immediately -->
    </div>
</template>

<script>
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
        props: {
            "playlistTrack": Object,
            "playlistId": String
        },

        data() {
            return {
                "trackDuration": ""
            }
        },

        created() {
            this.convertMilliseconds();
        },

        watch: {
            // 
        },

        methods: {
            removeTrack(e) {
                var track = e.target.value;
                spotifyApi.removeTracksFromPlaylist(this.playlistId, [track])
                    .then(function(data) {
                        console.log("Removed track from playlist.");
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            },

            convertMilliseconds() {
                var min = Math.floor(this.playlistTrack.trackDuration / 60000);
                var sec = ((this.playlistTrack.trackDuration % 60000) / 1000).toFixed(0);
                this.trackDuration = min + ":" + (sec < 10 ? '0' : '') + sec;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .remove-button {
        padding: 5px;
        position: absolute;
        top: 0px;
        right: 0px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 12px;
        display: none;
    }

    .track-block:hover .remove-button {
        display: inline-block;
    }

    .track-album-art {
        float: left;
        vertical-align: text-top;
        padding-right: 10px;
    }

    .track-name {
        float: left;
        font-size: 12px;
        font-weight: bold;

    }

    .track-artist {
        float: left;
        font-size: 12px;
    }

    .track-duration {
        float: left;
        font-size: 8px;
    }
</style>
