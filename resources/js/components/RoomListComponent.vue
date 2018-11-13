<template>
    <div class="row justify-content-center mb-3">
        <div class="col-12 col-sm-6">
            <ul class='list-group border'>
                <li class="list-group-item list-group-item-action" v-for="room in rooms"
                                                                   @click="joinRoom(room.id, room.hasAccess)">
                    <room-listing-component v-bind:room-id="room.id"
                                            v-bind:room-name="room.name"
                                            v-bind:has-access="room.hasAccess">
                    </room-listing-component>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'room-listing-component': require('./RoomListingComponent.vue')
        },
        props: {
            "csrfToken": String,
            "rooms": Array
        },
        methods: {
            joinRoom(id, hasAccess) {
                this.$emit("join-room", id, hasAccess);
            }
        },
    }
</script>

<style lang="scss" scoped>
    .list-group {
        height: 229px;
        overflow: scroll;
        overflow-y:scroll;
    }

    .list-group-item {
        padding: 5px 10px;
        border-left: 0;
        border-right: 0;
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
