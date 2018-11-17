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
    var SpotifyWebApi = require('spotify-web-api-js');
    var spotifyApi = new SpotifyWebApi();

    export default {
        data() {
            return {
                "genreList": [
                                {name: "New Release", value: "new-release"},
                                {name: "Acoustic", value: "acoustic"},
                                {name: "Afrobeat", value: "afrobeat"},
                                {name: "Alternative Rock", value: "alt-rock"},
                                {name: "Ambient", value: "ambient"},
                                {name: "Anime", value: "anime"},
                                {name: "Black Metal", value: "black-metal"},
                                {name: "Bluegrass", value: "bluegrass"},
                                {name: "Blues", value: "blues"},
                                {name: "Bossanova", value: "bossanova"},
                                {name: "Brazil", value: "brazil"},
                                {name: "Breakbeat", value: "breakbeat"},
                                {name: "British", value: "british"},
                                {name: "Cantopop", value: "cantopop"},
                                {name: "Chicago House", value: "chicago-house"},
                                {name: "Children", value: "children"},
                                {name: "Chill", value: "chill"},
                                {name: "Classical", value: "classical"},
                                {name: "Club", value: "club"},
                                {name: "Comedy", value: "comedy"},
                                {name: "Country", value: "country"},
                                {name: "Dance", value: "dance"},
                                {name: "Death Metal", value: "death-metal"},
                                {name: "Deep House", value: "deep-house"},
                                {name: "Detroit Techno", value: "detroit-techno"},
                                {name: "Disco", value: "disco"},
                                {name: "Dubstep", value: "dubstep"},
                                {name: "EDM", value: "edm"},
                                {name: "Electronic", value: "electronic"},
                                {name: "Emo", value: "emo"},
                                {name: "Folk", value: "folk"},
                                {name: "French", value: "french"},
                                {name: "Funk", value: "funk"},
                                {name: "Garage", value: "garage"},
                                {name: "German", value: "german"},
                                {name: "Gospel", value: "gospel"},
                                {name: "Goth", value: "goth"},
                                {name: "Groove", value: "groove"},
                                {name: "Guitar", value: "guitar"},
                                {name: "Hard Rock", value: "hard-rock"},
                                {name: "Hardstyle", value: "hardstyle"},
                                {name: "Heavy Metal", value: "heavy-metal"},
                                {name: "Hip-Hop", value: "hip-hop"},
                                {name: "Holidays", value: "holidays"},
                                {name: "House", value: "house"},
                                {name: "Indian", value: "indian"},
                                {name: "Indie", value: "indie"},
                                {name: "Indie-Pop", value: "indie-pop"},
                                {name: "Industrial", value: "industrial"},
                                {name: "Iranian", value: "iranian"},
                                {name: "Indie", value: "indie"},
                                {name: "J-Pop", value: "j-pop"},
                                {name: "J-Rock", value: "j-rock"},
                                {name: "Jazz", value: "jazz"},
                                {name: "K-Pop", value: "k-pop"},
                                {name: "Latin", value: "latin"},
                                {name: "Mandopop", value: "mantopop"},
                                {name: "Opera", value: "opera"},
                                {name: "Party", value: "party"},
                                {name: "Piano", value: "piano"},
                                {name: "Pop", value: "pop"},
                                {name: "Punk", value: "punk"},
                                {name: "Punk Rock", value: "punk-rock"},
                                {name: "R&B", value: "r-n-b"},
                                {name: "Rock", value: "rock"},
                                {name: "Romance", value: "romance"},
                                {name: "Sad", value: "sad"},
                                {name: "Sleep", value: "sleep"},
                                {name: "Spanish", value: "spanish"},
                                {name: "Study", value: "study"},
                                {name: "Summer", value: "summer"},
                                {name: "Techno", value: "techno"},
                                {name: "Trance", value: "trance"},
                                {name: "Work Out", value: "work-out"}
                            ]
            };
        },
        methods: {
            // Fetch tracks from a genre and add them to the room's playlist
            fetchGenreTracks(genre) {
                spotifyApi.getRecommendations({limit: 50, seed_genres: genre, min_popularity: 50})
                    .then(function(data) {
                        this.$emit("generateResults", data.tracks);
                    }.bind(this))
                    .catch(function(error) {
                        console.error(error);
                        console.log("Response status: " + error.status);
                        // If the response is 401 Unauthorized Error, call parent function to refresh the access token
                        if (error.status === 401) {
                            this.$emit("refreshToken");
                        }
                    }.bind(this));
            }
        }
    }
</script>

<style lang="scss" scoped>
    .card {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-top-width: 0;
    }

    .genre-div {
        height: 276px;
        overflow-y: scroll;
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
        cursor: pointer;
    }

    .notes {
        font-size: 12px;
    }
</style>
