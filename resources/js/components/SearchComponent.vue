<template>
    <div class="col-md-4">
        <div id="genres">
            <h2>Genres</h2>
            <button class="genre-type" v-on:click="getTracks($event)" value="new-release">New-Release</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="acoustic">Acoustic</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="anime">Anime</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="cantopop">Cantopop</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="chill">Chill</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="classical">Classical</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="country">Country</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="edm">EDM</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="hardstyle">Hardstyle</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="heavy-metal">Heavy-Metal</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="hip-hop">Hip-Hop</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="house">House</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="indie">Indie</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="indie-pop">Indie-Pop</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="j-pop">J-Pop</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="j-rock">J-Rock</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="jazz">Jazz</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="k-pop">K-Pop</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="mandopop">Mandopop</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="opera">Opera</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="r-n-b">R&B</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="rock">Rock</button><br>
            <button class="genre-type" v-on:click="getTracks($event)" value="techno">Techno</button><br>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            //
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

            getTracks: function(e) {
                this.tracks = [];
                var value = e.target.value;
                console.log("Genre selected: " + value);
                
                // todo: get the access token stored in database
                var accessToken = "BQCBm9GW_-k5lHv_1e9YcfwTfmKAIGDM8fX7O3O4YfyP4JpJieYCaF7ggGeAxjVEhoAP6F77stVFEBNj40ODHVIKgbhfXTVvYkqW0DDSXjNNSEaxaF-ZkZ0N4RJN6UjJFtLpb3BOJbP9rKPvRS2cEQVj6yVs7lYanJkqEZYPnm0UCe9ewKKbTYbWgPjcwIoP4aYyqlFmSa1e1ryVhILY_MYywe-jlVoYGYgJaFyvjr7XjSd9eVUDH0RPNDtQi3FnMsVa53BR6r0RadNNBKc";

                $.ajax({
                    url: "https://api.spotify.com/v1/recommendations",
                    data: {
                        seed_genres: value
                    },
                    headers: {
                        "Authorization" : "Bearer " + accessToken
                    },
                    success: function(response) {
                        // console.log(response.tracks);
                        for (var i = 0; i < response.tracks.length; i++) {
                            this.tracks.push(response.tracks[i].id);
                        }
                        console.log(this.tracks);
                        // console.log(this.tracks.join());

                    }.bind(this)
                });
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