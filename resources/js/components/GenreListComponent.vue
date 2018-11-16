<template>
    <div class="card">
        <div class="card-header">
            Genres
            <div class="notes">Click on a genre to add a song to your personal playlist.</div>
        </div>
        <div class="card-body genre-div">
            <ul class="genre-list" v-for="genre in genreList">
                <li class="genre-item" @click="fetchGenreTracks(genre.value)">
                    {{genre.name}}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();
    
    export default {
        data() {
            return {
                "genreList": [
                                {name: "Acoustic", value: "acoustic"},
                                {name: "Classical", value: "classical"},
                                {name: "Chill", value: "chill"},
                                {name: "Country", value: "country"},
                                {name: "Cantopop", value: "cantopop"},
                                {name: "Mandopop", value: "mantopop"},
                                {name: "K-Pop", value: "k-pop"},
                                {name: "J-Pop", value: "j-pop"},
                                {name: "EDM", value: "edm"},
                                {name: "Hip-Hop", value: "hip-hop"},
                                {name: "Heavy Metal", value: "heavy-metal"},
                                {name: "Jazz", value: "jazz"},
                                {name: "Indie", value: "indie"},
                                {name: "Opera", value: "opera"},
                                {name: "Pop", value: "pop"},
                                {name: "R&B", value: "r-n-b"},
                                {name: "Rock", value: "rock"},
                                {name: "Techno", value: "techno"}
                            ]
            };
        },
        methods: {
            // Fetch tracks from a genre and add them to the room's playlist
            fetchGenreTracks(genre) {
                spotifyApi.getRecommendations({limit: 50, seed_genres: genre})
                    .then(function(data) {
                        var track = {
                            trackName: data.tracks[0].name,
                            trackArtist: data.tracks[0].artists[0].name,
                            trackAlbumArt: data.tracks[0].album.images[0].url,
                            trackDuration: data.tracks[0].duration_ms,
                            trackUri: data.tracks[0].uri
                        };
                        this.$emit("addTrack", track);
                    }.bind(this))
                    .catch((err) => {
                        console.error(err);
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>
    .genre-div {
        font-size: 12px;
        -moz-column-count: 2;
        -moz-column-gap: 20px;
        -webkit-column-count: 2;
        -webkit-column-gap: 20px;
        column-count: 2;
    }

    .genre-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .genre-item {
        cursor: pointer;
        opacity: 1;
        transition: opacity .2s ease-out;
        -moz-transition: opacity .2s ease-out;
        -webkit-transition: opacity .2s ease-out;
        -o-transition: opacity .2s ease-out;
    }

    .genre-item:hover {
        opacity: 0.5;
    }

    .list-group-item {
        padding: 5px 10px;
        border-left: 0;
        border-right: 0;
    }

    .list-group-item {
        cursor: pointer;
    }

    .notes {
        font-size: 12px;
    }
</style>
