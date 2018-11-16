<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" v-text="header"></div>
                    <div class="card-body">
                        <div v-bind:rooms="rooms"
                             v-bind:is="bodyComponent"
                             v-on:set-body-component="setBodyComponent">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        components: {
            'home-landing-component': require('./HomeLandingComponent'),
            'home-create-component': require('./HomeCreateComponent'),
            'home-join-component': require('./HomeJoinComponent')
        },
        props: {
            "userId": String,
            "accessToken": String
        },
        data() {
            return {
                header: "Dashboard",
                bodyComponent: "home-landing-component",
                rooms: []
            }
        },
        created() {
            axios.defaults.headers.common["Authorization"] = "Bearer " + this.accessToken;
            this.initializeEventListeners();
            this.initializeRoomsList();
        },
        methods: {
            initializeEventListeners() {
                Echo.private("home")
                    .listen("RoomCreated", (data) => {
                        var room = data.room;
                        room["hasAccess"] = !room.isPrivate || (this.userId == data.user_id);
                        this.rooms.push(room);
                    });
            },
            initializeRoomsList() {
              axios.get("/api/rooms")
                  .then((res) => {
                      this.rooms = res.data;
                  });
            },
            setBodyComponent(component) {
                this.bodyComponent = component;
                if (component == "home-landing-component") {
                    this.header = "Dashboard"
                } else if (component == "home-create-component") {
                    this.header = "Create Room"
                } else if (component == "home-join-component") {
                    this.header = "Join Room"
                }
            }
        }
    }
</script>
