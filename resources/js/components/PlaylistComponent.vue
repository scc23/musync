<template>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class='clear-block border'>
                <button class='clear-button' v-on:click="clearPlaylist()">Clear all</button>
            </div>
            <ul class='list-group border'>
                <li class="list-group-item list-group-item-action" v-for="playlistTrack in playlistTracks">
                    <playlist-listing-component v-bind:playlistTrack="playlistTrack">
                    </playlist-listing-component>
                </li>
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
            "spotifyId": String
        },

        data() {
            return {
                "playlistId": "",
                "playlistTracks": [],
                "trackUris": []
            }
        },

        created() {
            this.refreshPlaylist();
        },

        watch: {
            //
        },

        methods: {
            refreshPlaylist() {
                spotifyApi.getUserPlaylists()
                    .then(function(data) {
                        for (var i = 0; i < data.items.length; i++) {
                            if (data.items[i].name == "MuSync") {
                                this.playlistId = data.items[i].id;
                            }
                        }
                    }.bind(this))
                    .then(function(data) {
                        console.log("playlist id: " + this.playlistId);
                        spotifyApi.getPlaylistTracks(this.playlistId)
                            .then(function(data) {
                                // console.log(data.items);
                                for (var i = 0; i < data.items.length; i++) {
                                    this.trackUris.push(data.items[i].track.uri);
                                    this.playlistTracks.push({
                                        trackName: data.items[i].track.name,
                                        trackArtist: data.items[i].track.artists[0].name,
                                        trackAlbumArt: data.items[i].track.album.images[0].url
                                    });
                                }
                                // console.log(this.playlistTracks);
                                console.log(this.trackUris);
                            }.bind(this))
                    }.bind(this))
                    .catch(function(error) {
                        axios.post("/api/token/refresh")
                        .then((res) => {
                            spotifyApi.setAccessToken(res.data.api_token);
                            axios.defaults.headers.common["Authorization"] = "Bearer " + res.data.api_token;
                            console.log("Access token refreshed.");
                        })
                        .catch((err) => {
                            console.error(err);
                        });
                    });
            },

            clearPlaylist() {
                spotifyApi.removeTracksFromPlaylist(this.playlistId, this.trackUris)
                    .then(function(data) {
                        console.log("Playlist cleared.");
                    })
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
            }
        },

        // mounted() {
        //     this.refreshPlaylist();
        // }
    }
</script>

<style lang="scss" scoped>
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
        height: 500px;
        overflow: scroll;
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