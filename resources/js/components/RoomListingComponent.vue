<template>
    <div>
        <div class="room-identification">
            <div class="room-name">{{roomName}}</div>
            <div class="room-id">{{roomId}}</div>
        </div>
        <span class="room-access-indicator">
          <font-awesome-icon icon="lock" v-if="!hasAccess"/>
          <!-- <font-awesome-icon icon="unlock" v-else/> -->
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            "roomId": String,
            "roomName": String,
            "hasAccess": Boolean
        }
    }
</script>

<style scoped>
    .room-identification {
        float: left;
    }

    .room-name {
        font-weight: bold
    }

    .room-access-indicator {
        float: right;
        padding-top: 10px;
    }
</style>
