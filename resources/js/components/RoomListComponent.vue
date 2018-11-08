<template>
    <div class="row justify-content-center mb-3">
        <div class="col-12 col-sm-6">
            <ul class='list-group border'>
                <li v-for="room in rooms" class="list-group-item list-group-item-action">
                    <div class="room-identification">
                        <div class="room-name">{{room.name}}</div>
                        <div class="room-id">{{room.id}}</div>
                    </div>
                    <span class="room-privacy-indicator"><!-- Icon --></span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "csrfToken": String,
            "accessToken": String
        },
        data() {
            return {
                // TODO - Remove test data
                rooms: [
                    {'id': "1111", 'name': 'Public 1', 'isPrivate': false},
                    {'id': "2222", 'name': 'Public 2', 'isPrivate': false},
                    {'id': "3333", 'name': 'Public 3', 'isPrivate': false},
                    {'id': "4444", 'name': 'Public 4', 'isPrivate': false},
                    {'id': "1111", 'name': 'Private 1', 'isPrivate': true},
                    {'id': "2222", 'name': 'Private 2', 'isPrivate': true},
                    {'id': "3333", 'name': 'Private 3', 'isPrivate': true},
                    {'id': "4444", 'name': 'Private 4', 'isPrivate': true},
                ]
            };
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
        border-bottom: 0;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .room-identification {
        float: left;
    }

    .room-name {
        font-weight: bold
    }

    .room-privacy-indicator {
        float: right;
    }
</style>
