<template>
	<div class="card">
        <div class="card-header">Search</div>
        <input type="text" placeholder="Search for a track">
    </div>
</template>

<script>
	var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
    	props: {
            "accessToken": String,
            "spotifyId": String,
            "playlistId": String
        },

        data() {
        	return {
        		//
        	};
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
        }
    }
</script>

<style lang="scss" scoped>

</style>