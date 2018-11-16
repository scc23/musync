<template>
    <div>
        <form class="input-group" v-on:submit.prevent="fetchTracks">
            <input class="form-control" type="text" v-model="searchInput" placeholder="Search for a song" width="100%">
            <button class="btn btn-default btn-primary" type="submit">Search</button>
        </form>
        <ul class='list-group border'>
            <li class="list-group-item list-group-item-action" v-for="track in searchResults"
                                                               @click="getTrackToAdd(track)">
                <search-tracks-listing-component v-bind:track="track">
                </search-tracks-listing-component>
                <span class="add-icon">+</span>
            </li>
        </ul>
    </div>
</template>

<script>
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
        components: {
            'search-tracks-listing-component': require('./searchTracksListingComponent.vue')
        },
        data() {
        	return {
        		"searchInput": "",
                "searchResults": []
        	};
        },
        watch: {
            //
        },
        methods: {
            fetchTracks() {
                this.searchResults = [];
                console.log(this.searchInput);
                spotifyApi.searchTracks(this.searchInput, {limit: 50})
                    .then(function(data) {
                        console.log(data.tracks.items);
                        for (var i = 0; i < data.tracks.items.length; i++) {
                            this.searchResults.push({
                                trackName: data.tracks.items[i].name,
                                trackArtist: data.tracks.items[i].artists[0].name,
                                trackAlbumArt: data.tracks.items[i].album.images[0].url,
                                trackDuration: data.tracks.items[i].duration_ms,
                                trackUri: data.tracks.items[i].uri
                            });
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        // Refresh access token if 401 error
                    }.bind(this));
            },
            getTrackToAdd(track) {
                this.$emit("addTrack", track);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .list-group {
        height: 250px;
        overflow-y:scroll;
    }

    .list-group-item {
        padding: 5px 10px;
        border-left: 0;
        border-right: 0;
    }

    .list-group-item {
        cursor: pointer;
    }

    .add-icon {
        font-size: 25px;
        position: absolute;
        top: 12px;
        right: 15px;
        display: none;
    }

    .list-group-item:hover .add-icon {
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