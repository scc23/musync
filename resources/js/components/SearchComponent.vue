<template>
    <div class="card">
        <div class="card-header">
            Genres
            <div class="notes">Click on a genre to add a track to the playlist queue.</div>
        </div>
        <div class="card-body">
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
            <button class="genre-type" v-on:click="fetchTracks($event)" value="pop">Pop</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="r-n-b">R&B</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="rock">Rock</button><br>
            <button class="genre-type" v-on:click="fetchTracks($event)" value="techno">Techno</button><br>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();
    
    export default {
        props: {
            "accessToken": String,
            "spotifyId": String,
            "playlistId": String
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
            // Fetch tracks from a genre and add them to the room's playlist
            fetchTracks(e) {
                var genre = e.target.value;
                this.trackUris = [];
                console.log("Genre selected: " + genre);

                spotifyApi.getRecommendations({seed_genres: genre})
                    .then(function(data) {
                        return data.tracks.map(function(t) { return t.uri });
                    })
                    .then(function(trackUris) {
                        // Add a single track to the playlist
                        spotifyApi.addTracksToPlaylist(this.playlistId, [trackUris[0]])
                            .then(function(data) {
                                console.log("Added a track to the playlist " + this.playlistId);
                            })
                            .catch(function(error) {
                                console.error(error);
                            })
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
            }
        },
        mounted() {
            spotifyApi.setAccessToken(this.accessToken);
            axios.defaults.headers.common["Authorization"] = "Bearer " + this.accessToken;
        }
    }
</script>

<style lang="scss" scoped>
    .notes {
        font-size: 12px;
    }

    .genre-type {
        background: none;
        border: none;
        cursor: pointer;
        opacity: 1;
        transition: opacity .2s ease-out;
        -moz-transition: opacity .2s ease-out;
        -webkit-transition: opacity .2s ease-out;
        -o-transition: opacity .2s ease-out;
    }

    .genre-type:hover {
        opacity: 0.5;
    }
</style>
