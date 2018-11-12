<template>
    <div>
        <div>
            <span class="track-album-art"><img width="50" height="50" v-bind:src="playlistTrack.trackAlbumArt"/></span>
            <span class="track-name">{{playlistTrack.trackName}}</span><br>
            <span class="track-artist">{{playlistTrack.trackArtist}}</span>
        </div>

        <!-- TODO: Click on track to play -->
        <!-- <span class="room-access-indicator">
          <font-awesome-icon icon="lock" v-if="!hasAccess"/>
        </span> -->
    </div>
</template>

<script>
    export default {
        props: {
            "playlistTrack": Object
        }
    }
</script>

<style lang="scss" scoped>
    .track-album-art {
        float: left;
        vertical-align: text-top;
        padding-right: 10px;
    }

    .track-name {
        float: left;
    }

    .track-artist {
        float: left;
        font-size: 12px;
    }
</style>
