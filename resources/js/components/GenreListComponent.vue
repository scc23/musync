<template>
    <div class="card">
        <div class="card-header">
            Genres
            <div class="notes">Click on a genre to add a song to the playlist queue.</div>
        </div>
        <div class="card-body">
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="acoustic">Acoustic</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="anime">Anime</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="cantopop">Cantopop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="chill">Chill</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="classical">Classical</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="country">Country</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="edm">EDM</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="hardstyle">Hardstyle</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="heavy-metal">Heavy-Metal</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="hip-hop">Hip-Hop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="house">House</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="indie">Indie</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="indie-pop">Indie-Pop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="j-pop">J-Pop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="j-rock">J-Rock</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="jazz">Jazz</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="k-pop">K-Pop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="mandopop">Mandopop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="opera">Opera</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="pop">Pop</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="r-n-b">R&B</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="rock">Rock</button><br>
            <button class="genre-type" v-on:click="fetchGenreTracks($event)" value="techno">Techno</button><br>
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
            "playlistId": String,
            "playlistTracks": Array
        },
        watch: {
            "playlistTracks": function(newState, oldState) {
                this.playlistTracks = newState;
            }
        },
        methods: {
            // Fetch tracks from a genre and add them to the room's playlist
            fetchGenreTracks(e) {
                var genre = e.target.value;
                spotifyApi.getRecommendations({seed_genres: genre})
                    .then(function(data) {
                        this.$emit("addTrack", data.tracks[0]);
                    }.bind(this))
                    .catch((err) => {
                        console.error(err);
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
