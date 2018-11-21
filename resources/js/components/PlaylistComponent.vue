<template>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class='clear-block content-border-left content-border-right'>
                <button class='clear-button' @click="updateClearPlaylist()">Clear all</button>
            </div>
            <ul class='list-group content-border'>
                <li class="list-group-item list-group-item-action" v-for="(playlistTrack, playlistTrackIndex) in playlistTracks"
                                                                   v-bind:class="{'current-track':currentTrack['trackUri'] == playlistTrack.trackUri}"
                                                                   @click="updateTrackToPlay(playlistTrackIndex, playlistTrack.trackUri)">
                    <playlist-listing-component v-bind:playlist-tracks="playlistTracks"
                                                v-bind:playlist-track="playlistTrack">
                    </playlist-listing-component>
                    <span class="remove-icon" v-on:click.stop="updatePlaylistRemoveTrack(playlistTrackIndex, playlistTrack.trackUri)">X</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'playlist-listing-component': require('./PlaylistListingComponent.vue')
        },
        props: {
            "spotifyId": String,
            "spotifyPlayerState": Object,
            "playlistTracks": Array,
            "userState": String
        },
        data() {
            return {
                "playlistId": "",
                "currentTrack": {name: "", artists: "", duration: 0, albumArt: "", trackUri: ""}
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
                    this.currentTrack["trackUri"] = this.spotifyPlayerState["track_window"]["current_track"]["uri"];
                    console.log("Currently playing track: " + this.currentTrack["name"]);
                }
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
            updateTrackToPlay(index, uri) {
                // Only the broadcaster can click on a track from the playlist to play
                if (this.userState == "broadcasting") {
                    // Pass the playlist index of the track to be played
                    var trackObject = {
                        index: index,
                        uri: uri
                    };
                    this.$emit("getTrack", trackObject);
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
        border-bottom-width: 0;
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

    .content-border {
        border: 1px solid #5e5e5e;
    }

    .content-border-left {
        border-left: 1px solid #5e5e5e;
    }

    .content-border-right {
        border-right: 1px solid #5e5e5e;
    }


    .list-group {
        height: 487px;
        overflow-y:scroll;
        border-bottom-left-radius: .25rem;
        border-bottom-right-radius: .25rem;
    }

    .list-group-item {
        padding: 5px 10px;
        border-left: 0;
        border-right: 0;
        cursor: pointer;
    }

    .remove-icon {
        font-size: 18px;
        position: absolute;
        top: 23px;
        right: 15px;
        display: none;
        background: none;
        border: none;
    }

    .list-group-item:hover .remove-icon{
        cursor: pointer;
        display: inline-block;
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