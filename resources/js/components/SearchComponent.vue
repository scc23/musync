<template>
    <div class="col-md-4">
        <!-- <form>
            <input type="text">
            <input type="submit" value="Search">
        </form> -->

        <!-- <input type="text" v-model="searchText" value="" placeholder="Search"> -->

        <!-- <div id="album-results">
            <div v-for="album in albums">
                {{album.name}}
            </div>
        </div>
        <div id="artist-results">
            <div v-for="artist in artists">
                {{artist.name}}
            </div>
        </div> -->

        <div id="genres">
            <h2>Genres</h2>
            <button class="genre-type">Acoustic</button><br>
            <button class="genre-type">Anime</button><br>
            <button class="genre-type">Cantopop</button><br>
            <button class="genre-type">Chill</button><br>
            <button class="genre-type">Classical</button><br>
            <button class="genre-type">Country</button><br>
            <button class="genre-type">EDM</button><br>
            <button class="genre-type">Hardstyle</button><br>
            <button class="genre-type">Heavy-Metal</button><br>
            <button class="genre-type">Hip-Hop</button><br>
            <button class="genre-type">House</button><br>
            <button class="genre-type">Indie</button><br>
            <button class="genre-type">Indie-Pop</button><br>
            <button class="genre-type">J-Pop</button><br>
            <button class="genre-type">J-Rock</button><br>
            <button class="genre-type">Jazz</button><br>
            <button class="genre-type">K-Pop</button><br>
            <button class="genre-type">Mandopop</button><br>
            <button class="genre-type">Opera</button><br>
            <button class="genre-type">R&B</button><br>
            <button class="genre-type">Rock</button><br>
            <button class="genre-type">Techno</button><br>
            <button class="genre-type">New-Release</button><br>
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
                // albums: [],
                // artists: [],
                // searchText: ""
            }
        },

        watch: {
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
            // // Search for albums in Spotify catalog, wait 500 ms before searching
            // searchAlbums: _.debounce(function(query, callback) {
            //     var accessToken = "BQB2ClUdMN7F-LBHNdqd2G1CyXAl9ZtkK-eALqt6-fF1GAomSengZt0-f8DulnplAy_4LhD7T2V6tFQghO-bpaPJZak5SS51KZjH069-nxlBf9MHsX1vcTKrd_GNdPXBHChvZvklCIT8sxjxxGMrMNYIHeEZrGZ1WeH5DYXPR4MmZpT5upNYRG799xJOOD8yPXP_Rly2oaVMh7XnjiWRj3dED5TLkQSs3oa9-_7vkNRXm-yQR6d4IwYQNGZS6LILjlVdPdQJdDc5r2eE7qM";
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
            //     var accessToken = "BQB2ClUdMN7F-LBHNdqd2G1CyXAl9ZtkK-eALqt6-fF1GAomSengZt0-f8DulnplAy_4LhD7T2V6tFQghO-bpaPJZak5SS51KZjH069-nxlBf9MHsX1vcTKrd_GNdPXBHChvZvklCIT8sxjxxGMrMNYIHeEZrGZ1WeH5DYXPR4MmZpT5upNYRG799xJOOD8yPXP_Rly2oaVMh7XnjiWRj3dED5TLkQSs3oa9-_7vkNRXm-yQR6d4IwYQNGZS6LILjlVdPdQJdDc5r2eE7qM";
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