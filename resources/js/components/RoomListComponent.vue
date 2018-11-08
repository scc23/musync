<template>
    <div class="row justify-content-center mb-3">
        <div class="col-12 col-sm-6">
            <ul class='list-group border'>
                <li class="list-group-item list-group-item-action" v-for="room in rooms" @click="joinRoom(room.id)">
                    <room-listing-component v-bind:room-id="room.id"
                                            v-bind:room-name="room.name"
                                            v-bind:is-private="room.isPrivate">
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
            "accessToken": String
        },
        data() {
            return {
                rooms: []
            };
        },
        methods: {
            refreshList() {
                axios.get("/api/rooms")
                .then((res) => {
                    this.rooms = res.data;

                    // Add border-bottom to last room listing if list is not
                    // filled until the bottom.
                    var borderBottom = '0';
                    if (this.rooms.length < 4) {
                        borderBottom = '1px';
                    }

                    $('.list-group-item:last-child').css({
                        'border-bottom': borderBottom
                    })
                });
            },
            joinRoom(id) {
                this.$emit("join-room", id);
            }
        },
        created() {
            this.refreshList();
        }
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
