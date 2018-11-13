<template>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class='clear-block border'>
                <button class='clear-button' v-on:click="clearPlaylist()">Clear all</button>
            </div>
            <ul class='list-group border'>
                    <li class="list-group-item list-group-item-action" v-for="playlistTrack in playlistTracks"
                                                                       v-bind:class="{'current-track':currentTrack['name'] == playlistTrack.trackName}"
                                                                       @click="updateTrackToPlay(playlistTrack.trackUri)">
                        <playlist-listing-component v-bind:playlist-track="playlistTrack"
                                                    v-bind:playlist-id="playlistId">
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
            "trackToPlay": String
        },
        data() {
            return {
                "playlistId": "",
                "playlistTracks": [],
                "currentTrack": {name: "", artists: "", duration: 0, albumArt: ""},
                // "trackSelected": String
            }
        },
        created() {
            this.refreshPlaylist();
        },
        watch: {
            "spotifyPlayerState": function(newState, oldState) {
                this.spotifyPlayerState = newState;
                this.isPaused = this.spotifyPlayerState["paused"];
                this.currentTrack["name"] = this.spotifyPlayerState["track_window"]["current_track"]["name"];
                this.currentTrack["artists"] = this.spotifyPlayerState["track_window"]["current_track"]["artists"][0]["name"];
                this.currentTrack["duration"] = this.spotifyPlayerState["track_window"]["current_track"]["duration_ms"];
                this.currentTrack["albumArt"] = this.spotifyPlayerState["track_window"]["current_track"]["album"]["images"][0]["url"];
                console.log("Currently playing track: " + this.currentTrack["name"]);
            }
        },
        methods: {
            refreshPlaylist() {
                // Get the playlist id of the MuSync playlist
                spotifyApi.getUserPlaylists()
                    .then(function(data) {
                        for (var i = 0; i < data.items.length; i++) {
                            if (data.items[i].name == "MuSync") {
                                this.playlistId = data.items[i].id;
                            }
                        }
                    }.bind(this))
                    .then(function(data) {
                        console.log("Playlist id: " + this.playlistId);
                        // Get the tracks in the MuSync playlist
                        spotifyApi.getPlaylistTracks(this.playlistId)
                            .then(function(data) {
                                // Store the tracks in playlistTracks
                                // console.log(data.items);
                                for (var i = 0; i < data.items.length; i++) {
                                    this.playlistTracks.push({
                                        trackName: data.items[i].track.name,
                                        trackArtist: data.items[i].track.artists[0].name,
                                        trackAlbumArt: data.items[i].track.album.images[0].url,
                                        trackDuration: data.items[i].track.duration_ms,
                                        trackUri: data.items[i].track.uri
                                    });
                                }
                                // console.log(this.playlistTracks);
                            }.bind(this))
                            .catch(function(error) {
                                console.error(error);
                            })
                    }.bind(this))
                    .catch(function(error) {
                        axios.post("/api/token/refresh")
                        .then((res) => {
                            axios.defaults.headers.common["Authorization"] = "Bearer " + res.data.api_token;
                            console.log("Access token refreshed.");
                        })
                        .catch((err) => {
                            console.error(err);
                        });
                    });
            },
            clearPlaylist() {
                // Get the track uris from playlistTracks
                var tracks = [];
                for (var i = 0; i < this.playlistTracks.length; i++) {
                    tracks.push(this.playlistTracks[i].trackUri);
                }
                // Remove all the tracks from the MuSync playlist
                spotifyApi.removeTracksFromPlaylist(this.playlistId, tracks)
                    .then(function(data) {
                        console.log("Playlist cleared.");
                        this.playlistTracks = [];
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        // axios.post("/api/token/refresh")
                        // .then((res) => {
                        //     spotifyApi.setAccessToken(res.data.api_token);
                        //     axios.defaults.headers.common["Authorization"] = "Bearer " + res.data.api_token;
                        //     console.log("Access token refreshed.");
                        // })
                        // .catch((err) => {
                        //     console.error(err);
                        // });
                    });
            },
            updateTrackToPlay(value) {
                // console.log("Clicked on track: " + value);
                this.$emit('update', value);
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