<template>
    <div>
        <div id="genres">
            <h2>Genres</h2>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="new-release">New-Release</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="acoustic">Acoustic</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="anime">Anime</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="cantopop">Cantopop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="chill">Chill</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="classical">Classical</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="country">Country</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="edm">EDM</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="hardstyle">Hardstyle</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="heavy-metal">Heavy-Metal</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="hip-hop">Hip-Hop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="house">House</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="indie">Indie</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="indie-pop">Indie-Pop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="j-pop">J-Pop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="j-rock">J-Rock</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="jazz">Jazz</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="k-pop">K-Pop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="mandopop">Mandopop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="opera">Opera</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="r-n-b">R&B</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="rock">Rock</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="techno">Techno</button><br>
        </div>
    </div>
</template>

<script>
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();
    export default {
        props: {
            "accessToken": String,
            "spotifyId": String
        },

        data() {
            return {
                trackUris: [],
                playlistId: ""
            }
        },

        watch: {
            // Function for search feature
            // Watch what user types in the search box
            // searchText: function() {
            //     let self = this;
            //     // Clear albums and artists array once user starts typing in search box
            //     self.albums = {};
            //     self.artists = {};

            //     // Check if albums and artists arrays are empty
            //     if (self.searchText.length > 0) {
            //         var query = self.searchText.toLowerCase();
            //         query.replace(/ /gi, "-");
            //         self.searchAlbums(query, function(response) {
            //             self.albums = response;
            //         });
            //         self.searchArtists(query, function(response) {
            //             self.artists = response;
            //         });
            //     }
            // }
        },

        methods: {
            // Get the MuSync playlist id from the user's playlists
            init() {
                spotifyApi.setAccessToken(this.accessToken);
                spotifyApi.getUserPlaylists(this.spotifyId)
                    .then(function(data) {
                        // Find MuSync playlist
                        for (var i = 0; i < data.items.length; i++) {
                            if (data.items[i].name == "MuSync") {
                                console.log(data.items[i].id);
                                this.playlistId = data.items[i].id;
                            }
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    })
                return this.playlistId;
            },
            // Fetch tracks from a genre and add them to the room's playlist
            fetchTracks(e) {
                var genre = e.target.value;
                this.trackUris = [];
                console.log("Genre selected: " + genre);
                spotifyApi.setAccessToken(this.accessToken);

                spotifyApi.getRecommendations({seed_genres: genre})
                    .then(function(data) {
                        return data.tracks.map(function(t) { return t.uri });
                    })
                    .then(function(trackUris) {
                        for (var i = 0; i < trackUris.length; i++) {
                            this.trackUris.push(trackUris[i]);
                            console.log(this.trackUris[i]);
                        }
                        // Replace tracks in playlist with updated tracks
                        spotifyApi.replaceTracksInPlaylist(this.playlistId, trackUris)
                            .then(function(data) {
                                console.log(data);
                            })
                            .catch(function(error) {
                                console.error(error);
                            })
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                    });
            }
            // Functions for search feature
            // Search for albums
            // searchAlbums: _.debounce(function(query, callback) {
            //     $.ajax({
            //         url: "https://api.spotify.com/v1/search",
            //         data: {
            //             q: query,
            //             type: "album"
            //         },
            //         headers: {
            //             "Authorization" : "Bearer " + this.accessToken
            //         },
            //         success: function(response) {
            //             callback(response.albums.items);
            //         }
            //     });
            // }, 500),

            // Search for artists
            // searchArtists: _.debounce(function(query, callback) {
            //     $.ajax({
            //         url: "https://api.spotify.com/v1/search",
            //         data: {
            //             q: query,
            //             type: "artist"
            //         },
            //         headers: {
            //             "Authorization" : "Bearer " + this.accessToken
            //         },
            //         success: function(response) {
            //             callback(response.artists.items);
            //         }
            //     });
            // }, 500)
        },
        mounted() {
            this.init();
        }
    }
</script>

<style scoped>
    .genre-type {
        background: none;
        border: none;
        cursor: pointer;
        opacity: 1;
        transition: opacity .5s ease-out;
        -moz-transition: opacity .5s ease-out;
        -webkit-transition: opacity .5s ease-out;
        -o-transition: opacity .5s ease-out;
    }
    .genre-type:hover {
        opacity: 0.5;
    }
</style>
