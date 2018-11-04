<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" v-text="header"></div>
                    <div class="card-body">
                        <div v-if="displayBlock == 'landing'">
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
                        <div v-else-if="displayBlock == 'create'">
                            <form method="POST" @submit.prevent="submitCreateRoom">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-6 form-group">
                                        <label for="create-room-name" class="mb-1 control-label">Room Name</label>
                                        <input type="text" name="create-room-name" id="create-room-name" class="form-control"
                                            placeholder="Room Name" v-model="createName" @change="clearCreateRoomNameError">
                                        <span class="help-block">{{createNameError}}</span>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-6 form-group">
                                        <label for="create-room-password" class="mb-1">Password</label>
                                        <input type="password" name="create-room-password" id="create-room-password" class="form-control"
                                            placeholder="Password" v-model="createPassword" :disabled="!isPrivate" @change="clearCreateRoomPasswordError">
                                        <span class="help-block">{{createPasswordError}}</span>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-6 form-group">
                                        <input type="checkbox" id="create-room-private" class="form-check-label mr-1" v-model="isPrivate" @click="clearPassword">
                                        <label for="create-room-private">Private</label>
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-2">
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
                        <div v-else-if="displayBlock == 'join'">
                            <form method="POST" @submit.prevent="submitJoinRoom">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-6 form-group">
                                        <label for="join-room-id" class="mb-1 control-label">Room ID</label>
                                        <input type="text" id="join-room-id" class="form-control" placeholder="Room ID" v-model="joinId"
                                            @change="clearJoinRoomErrors">
                                        <span class="help-block">{{joinIdError}}</span>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-6 form-group" v-if="joinHasPassword">
                                        <label for="join-room-password" class="mb-1 control-label">Password</label>
                                        <input type="password" name="join-room-password" id="join-room-password" class="form-control"
                                            placeholder="Password" v-model="joinPassword" @change="clearJoinRoomPasswordError">
                                        <span class="help-block">{{joinPasswordError}}</span>
                                    </div>
                                </div>
                                <div class="row justify-content-center mb-2">
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
    import axios from "axios";

    export default {
        props: {
            "csrfToken": String,
            "accessToken": String
        },

        data() {
            return {
                header: "Dashboard",
                displayBlock: "landing",
                createName: "",
                createNameError: "",
                createPassword: "",
                createPasswordError: "",
                isPrivate: false,
                joinId: "",
                joinPassword: "",
                joinIdError: "",
                joinPasswordError: "Join password error",
                joinHasPassword: false
            }
        },

        mounted() {
            axios.defaults.headers.common["Authorization"] = "Bearer " + this.accessToken;
        },

        methods: {
            resetState() {
              this.createName = "";
              this.createPassword = "";
              this.isPrivate = false;
              this.joinId = "";
              this.joinPassword = "";
              this.clearCreateRoomNameError();
              this.clearCreateRoomPasswordError();
              this.clearJoinRoomErrors();
              this.clearJoinRoomPasswordError();
            },
            promptCreateRoom() {
                this.header = "Create Room";
                this.displayBlock = "create";
                this.resetState();
            },
            promptJoinRoom() {
                this.header = "Join Room";
                this.displayBlock = "join";
                this.resetState();
            },
            returnHome() {
                this.header = "Dashboard";
                this.displayBlock = "landing";
            },
            clearPassword() {
                // Condition run when isPrivate = true because @click
                // is processed before isPrivate is set.
                if (this.isPrivate) {
                    this.createPassword = "";
                    this.clearCreateRoomPasswordError();
                }
            },
            clearCreateRoomNameError() {
                this.createNameError = "";
            },
            clearCreateRoomPasswordError() {
                this.createPasswordError = "";
            },
            clearJoinRoomErrors() {
                this.joinIdError = "";
                this.joinHasPassword = false;
                this.clearJoinRoomPasswordError();
            },
            clearJoinRoomPasswordError() {
                this.joinPasswordError = "";
            },
            submitCreateRoom() {
                if (this.validateCreateRoom()) {
                    axios.post("/api/rooms", {
                        name: this.createName,
                        password: this.createPassword
                    })
                    .then((res) => {
                        var roomId = res.data.id;
                        document.location.pathname = "/room/" + roomId;
                    })
                    .catch((err) => {
                        var body = err.response.data;
                        if (body['createNameError']) {
                            this.createNameError = body['createNameError'];
                        }

                        if (body['createPasswordError']) {
                            this.createPasswordError = body['createPasswordError'];
                        }
                    });
                }
            },
            validateCreateRoom() {
                  var isValid = true;
                  if (!this.createName.trim()) {
                      this.createNameError = "Room name cannot be empty.";
                      isValid = false;
                  }
                  if (this.isPrivate && !this.createPassword) {
                      this.createPasswordError = "A private room may not have an empty password.";
                      isValid = false;
                  }

                  return isValid;
            },
            submitJoinRoom() {
                if (this.validateJoinRoom()) {
                    var roomId = this.joinId;
                    axios.post("/api/room/" + roomId + "/membership", {
                        password: this.joinPassword
                    })
                    .then((res) => {
                        document.location.pathname = "/room/" + roomId;
                    })
                    .catch((err) => {
                        var body = err.response.data;
                        if (body['joinIdError']) {
                            this.joinIdError = body['joinIdError'];
                        }

                        if (this.joinHasPassword && body['joinPasswordError']) {
                            this.joinPasswordError = body['joinPasswordError'];
                        } else if (body['joinPasswordError']) {
                            this.joinPassword = "";
                            this.joinHasPassword = true;
                            this.joinPasswordError = "This room is a private room, enter the password."
                        }
                    });
                }
            },
            validateJoinRoom() {
                var isValid = true;

                if (!this.joinId || this.joinId.length != 4) {
                    this.joinIdError = "The ID must be 4 characters long.";
                    isValid = false;
                }

                if (this.joinHasPassword && this.joinPassword.length == 0) {
                    this.joinPasswordError = "Please enter a password.";
                    isValid = false;
                }

                return isValid;
            }
        }
    }
</script>
