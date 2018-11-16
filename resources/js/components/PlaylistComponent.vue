<template>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class='clear-block border'>
                <button class='clear-button' @click="updateClearPlaylist()">Clear all</button>
            </div>
            <ul class='list-group border'>
                    <li class="list-group-item list-group-item-action" v-for="(playlistTrack, playlistTrackIndex) in playlistTracks"
                                                                       v-bind:class="{'current-track':currentTrack['name'] == playlistTrack.trackName}"
                                                                       @click="updateTrackToPlay(playlistTrackIndex)">
                        <playlist-listing-component v-bind:playlist-tracks="playlistTracks"
                                                    v-bind:playlist-track="playlistTrack"
                                                    v-bind:playlist-id="playlistId"
                                                    v-bind:playlist-track-index="playlistTrackIndex"
                                                    @getTrackToRemove="updatePlaylistRemoveTrack">
                        </playlist-listing-component>
                    </li>
                </span>
            </ul>
        </div>
    </div>
</template>

<script>
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
        components: {
            'playlist-listing-component': require('./PlaylistListingComponent.vue')
        },
        props: {
            "accessToken": String,
            "spotifyId": String,
            "spotifyPlayerState": Object,
            "trackToPlay": Number,
            "playlistTracks": Array,
            "hasBroadcaster": Boolean
        },
        data() {
            return {
                "playlistId": "",
                "currentTrack": {name: "", artists: "", duration: 0, albumArt: ""}
            }
        },
        watch: {
            "spotifyPlayerState": function(newState, oldState) {
                this.spotifyPlayerState = newState;
                if (this.spotifyPlayerState !== null) {
                    this.isPaused = this.spotifyPlayerState["paused"];
                    this.currentTrack["name"] = this.spotifyPlayerState["track_window"]["current_track"]["name"];
                    this.currentTrack["artists"] = this.spotifyPlayerState["track_window"]["current_track"]["artists"][0]["name"];
                    this.currentTrack["duration"] = this.spotifyPlayerState["track_window"]["current_track"]["duration_ms"];
                    this.currentTrack["albumArt"] = this.spotifyPlayerState["track_window"]["current_track"]["album"]["images"][0]["url"];
                    console.log("Currently playing track: " + this.currentTrack["name"]);
                }
            },
            "playlistTracks": function(newState, oldState) {
                this.playlistTracks = newState;
            }
        },
        methods: {
            updateClearPlaylist() {
                // Call parent function to clear the entire playlist
                this.$emit("change");
            },
            updatePlaylistRemoveTrack(index, uri) {
                // Call parent function to remove track from playlist
                this.$emit("removeTrack", index, uri);
            },
            updateTrackToPlay(value) {
                // Only the broadcaster can click on a track from the playlist to play
                if (this.hasBroadcaster == true) {
                    // Pass the playlist index of the track to be played
                    this.$emit("getTrack", value);
                }
                else {
                    console.log("Cannot play track, only the broadcaster can play a track.");
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .current-track {
        background-color: #c9c9c9;
    }

    .clear-block {
        padding-left: 5px;
    }

    .clear-button {
        background: none;
        border: none;
        cursor: pointer;
        opacity: 1;
        transition: opacity .2s ease-out;
        -moz-transition: opacity .2s ease-out;
        -webkit-transition: opacity .2s ease-out;
        -o-transition: opacity .2s ease-out;
    }

    .clear-button:hover {
        opacity: 0.5;
    }

    .list-group {
        height: 535px;
        /*overflow: scroll;*/
        overflow-y:scroll;
    }

    .list-group-item {
        padding: 5px 10px;
        border-left: 0;
        border-right: 0;
    }

    .list-group-item:first-child {
        border-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .list-group-item:last-child {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
</style>