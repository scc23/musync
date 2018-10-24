<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" v-text="header"></div>
                    <div class="card-body">
                        <div id="home-landing" v-if="showLanding">
                            <div class="row justify-content-center mb-2">
                                <div class="col-12 col-sm-6">
                                    <button class="home-btn btn btn-primary btn-block" @click="promptCreateRoom">
                                    Create Room
                                    </button>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-6">
                                    <button type="button" class="home-btn btn btn-primary btn-block" @click="promptJoinRoom">
                                    Join Room
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="home-create" v-if="showCreate">
                            <form method="POST" action="/rooms">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <div class="row justify-content-center mb-2">
                                    <div class="col-12 col-sm-6">
                                        <label for="create-room-name" class="mb-1">Room Name</label>
                                        <input type="text" name="create-room-name" id="create-room-name" class="form-control" placeholder="Room Name">
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-2">
                                    <div class="col-12 col-sm-6">
                                        <label for="create-room-password" class="mb-1">Password</label>
                                        <input type="password" name="create-room-password" id="create-room-password" class="form-control" placeholder="Password" v-model="password" :disabled="!isPrivate">
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-1">
                                    <div class="col-12 col-sm-6">
                                        <input type="checkbox" id="create-room-private" class="form-check-label mr-1" v-model="isPrivate" @click="clearPassword">
                                        <label for="create-room-private">Private</label>
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-4">
                                    <div class="col-12 col-sm-6">
                                        <input type="submit" class="home-btn btn btn-primary btn-block" value="Create">
                                    </div>
                                </div>
                            </form>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-6">
                                    <button type="button" class="home-btn btn btn-primary btn-block" @click="returnHome">
                                    Back
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="home-join" v-if="showJoin">
                            <form method="POST" :action="joinPath">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <div class="row justify-content-center mb-2">
                                    <div class="col-12 col-sm-6">
                                        <label for="join-room-id" class="mb-1">Room ID</label>
                                        <input type="text" id="join-room-id" class="form-control" placeholder="Room ID" v-model="joinId">
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-2">
                                    <div class="col-12 col-sm-6">
                                        <label for="join-room-password" class="mb-1">Password</label>
                                        <input type="password" name="join-room-password" id="join-room-password" class="form-control" placeholder="Password" v-model="joinPassword">
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-4">
                                    <div class="col-12 col-sm-6">
                                        <input type="submit" class="home-btn btn btn-primary btn-block" value="Join">
                                    </div>
                                </div>
                            </form>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-6">
                                    <button type="button" class="home-btn btn btn-primary btn-block" @click="returnHome">
                                    Back
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "csrfToken": String
        },

        data() {
            return {
                header: "Dashboard",
                showLanding: true,
                showCreate: false,
                showJoin: false,
                password: "",
                isPrivate: false,
                joinId: "",
                joinPassword: ""
            }
        },

        methods: {
            promptCreateRoom() {
                this.header = "Create Room";
                this.showLanding = false;
                this.showCreate = true;
            },
            promptJoinRoom() {
                this.header = "Join Room";
                this.showLanding = false;
                this.showJoin = true;
            },
            returnHome() {
                this.header = "Dashboard";
                this.showCreate = false;
                this.showJoin = false;
                this.showLanding = true;
            },
            clearPassword() {
                // Condition run when isPrivate = true because @click
                // is processed before isPrivate is set.
                if (this.isPrivate) {
                    this.password = "";
                }
            }
        },

        computed: {
            joinPath() {
                return "/room/" + this.joinId + "/membership";
            }
        }
    }
</script>
