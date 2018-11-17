<template>
    <div>
        <form class="input-group" v-on:submit.prevent="fetchTracks">
            <input id="text-search" class="form-control" type="text" v-model="searchInput" placeholder="Search for a song" width="100%">
            <span class="input-group-append">
                <button class="btn btn-default btn-primary" id="btn-search" type="submit">Search</button>
            </span>
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
        props: {
            "searchResults": Array
        },
        data() {
        	return {
        		"searchInput": "",
                // "searchResults": []
        	};
        },
        methods: {
            isValidInput: function() {
                return (this.searchInput.trim().length != 0);
            },
            fetchTracks() {
                if (this.isValidInput()) {
                    console.log("Fetching tracks...");
                    spotifyApi.searchTracks(this.searchInput, {limit: 50})
                        .then(function(data) {
                            this.$emit("generateResults", data.tracks.items);
                        }.bind(this))
                        .catch(function(error) {
                            console.error(error);
                            console.log("Response status: " + error.status);
                            // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                            if (error.status === 401) {
                                $this.emit("refreshToken");
                            }
                        }.bind(this));
                }
            },
            getTrackToAdd(track) {
                this.$emit("addTrack", track);
            }
        },
        watch: {
            searchInput: function() {
                if (this.isValidInput()) {
                    $('#btn-search').prop('disabled', false);
                } else {
                    $('#btn-search').prop('disabled', true);
                }
            }
        },
        mounted() {
            $('#btn-search').prop('disabled', true);
        }
    }
</script>

<style lang="scss" scoped>
    #text-search {
        border-bottom-width: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    #btn-search {
        border-bottom-right-radius: 0;
    }

    .list-group {
        height: 273px;
        overflow-y:scroll;
    }

    .list-group-item {
        padding: 5px 10px;
        border-left: 0;
        border-right: 0;
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
