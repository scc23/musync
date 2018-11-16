<template>
    <div class="track-block">
        <div>
            <span class="track-album-art"><img width="70" height="70" v-bind:src="playlistTrack.trackAlbumArt"/></span>
            <span class="track-name">{{ shortenString(playlistTrack.trackName) }}</span><br>
            <span class="track-artist" >{{ shortenString(playlistTrack.trackArtist) }}</span>
            <button class="remove-button" v-on:click="removeTrack($event)">X</button><br>
            <span class="track-duration">{{trackDuration}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "playlistTrack": Object,
            "playlistId": String,
            "playlistTrackIndex": Number
        },
        data() {
            return {
                "trackDuration": ""
            }
        },
        created() {
            this.convertMilliseconds();
        },
        methods: {
            removeTrack(e) {
                // Call parent function to call another parent function to remove track from playlist
                this.$emit("getTrackToRemove", this.playlistTrackIndex, this.playlistTrack.trackUri);
            },
            convertMilliseconds() {
                // Convert track duration in milliseconds to minutes:seconds
                var min = Math.floor(this.playlistTrack.trackDuration / 60000);
                var sec = ((this.playlistTrack.trackDuration % 60000) / 1000).toFixed(0);
                this.trackDuration = min + ":" + (sec < 10 ? '0' : '') + sec;
            },
            shortenString(s) {
                // Take substring if string is too long to display in one line
                if (s.length > 35) {
                    return (s.substring(0,35) + "...");
                }
                else {
                    return s;
                }
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