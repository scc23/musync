<template>
    <div class="col-md-4">
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
    export default {
        props: {
            "accessToken": String
        },

        data() {
            return {
                tracks: []
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
            // todo: Create a playlist with generated track id's
            // createPlaylist: function() {
            //     var accessToken = "{access_token}";
            //     var playlistId = "{playlist_id}";

            //     $.ajax({
            //         url: "https://api.spotify.com/v1/{playlist_id}/tracks",
            //         data: {
            //             name: "MuSync",
            //             public: true,
            //             playlist_id: playlistId
            //         },
            //         headers: {
            //             "Authorization" : "Bearer " + accessToken
            //         },
                    
            //     });
            // },

            fetchTracks: function(e) {
                this.tracks = [];
                var value = e.target.value;
                console.log("Genre selected: " + value);
                
                // Use the user's access token from the database
                $.ajax({
                    url: "https://api.spotify.com/v1/recommendations",
                    data: {
                        seed_genres: value
                    },
                    headers: {
                        "Authorization" : "Bearer " + this.accessToken
                    },
                    success: this.saveTracks
                });
            },

            saveTracks: function(response) {
                for (var i = 0; i < response.tracks.length; i++) {
                    this.tracks.push(response.tracks[i]);
                }
                console.log(this.tracks);
            }

            // Functions for search feature
            // // Search for albums in Spotify catalog, wait 500 ms before searching
            // searchAlbums: _.debounce(function(query, callback) {
            //     var accessToken = "{access_token}";
            //     $.ajax({
            //         url: "https://api.spotify.com/v1/search",
            //         data: {
            //             q: query,
            //             type: "album"
            //         },
            //         headers: {
            //             "Authorization" : "Bearer " + accessToken
            //         },
            //         success: function(response) {
            //             callback(response.albums.items);
            //         }
            //     });
            // }, 500),

            // // Search for artists in Spotify catalog, wait 500 ms before searching
            // searchArtists: _.debounce(function(query, callback) {
            //     var accessToken = "{access_token}";
            //     $.ajax({
            //         url: "https://api.spotify.com/v1/search",
            //         data: {
            //             q: query,
            //             type: "artist"
            //         },
            //         headers: {
            //             "Authorization" : "Bearer " + accessToken
            //         },
            //         success: function(response) {
            //             callback(response.artists.items);
            //         }
            //     });
            // }, 500)
        },

        computed: {
            //
        }
    }
</script>