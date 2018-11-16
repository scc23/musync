<template>
    <div>
        <form method="POST" @submit.prevent="submitJoin">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 form-group">
                    <label for="join-room-id" class="mb-1 control-label">Room ID</label>
                    <input type="text" id="join-room-id" class="form-control" placeholder="Room ID" v-model="joinId"
                        @change="clearRoomErrors">
                    <span class="help-block">{{joinIdError}}</span>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 form-group" v-if="joinHasPassword">
                    <label for="join-room-password" class="mb-1 control-label">Password</label>
                    <input type="password" name="join-room-password" id="join-room-password" class="form-control"
                        placeholder="Password" v-model="joinPassword" @change="clearRoomPasswordError">
                    <span class="help-block">{{joinPasswordError}}</span>
                </div>
            </div>
            <room-list-component v-bind:rooms="rooms"
                                 v-on:join-room="setIdAndSubmit">
            </room-list-component>
            <div class="row justify-content-center mb-2">
                <div class="col-12 col-sm-6">
                    <input type="submit" class="home-btn btn btn-primary btn-block" value="Join">
                </div>
            </div>
        </form>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6">
                <button type="button" class="home-btn btn btn-primary btn-block" @click="returnLanding">
                    Back
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
            'room-list-component': require('./RoomListComponent.vue')
        },
        props: {
            "rooms": Array
        },
        data() {
            return {
              joinId: "",
              joinPassword: "",
              joinIdError: "",
              joinPasswordError: "Join password error",
              joinHasPassword: false
            };
        },
        methods: {
            returnLanding(event) {
                this.$emit("set-body-component", "home-landing-component");
            },
            clearRoomErrors() {
                this.joinIdError = "";
                this.joinHasPassword = false;
                this.clearRoomPasswordError();
            },
            clearRoomPasswordError() {
                this.joinPasswordError = "";
            },
            indicateRoomHasPassword() {
                this.joinPassword = "";
                this.joinHasPassword = true;
                this.joinPasswordError = "This room is a private room, enter the password."
            },
            validateInput() {
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
            },
            submitJoin() {
                if (this.validateInput()) {
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
                            this.indicateRoomHasPassword();
                        }
                    });
                }
            },
            setIdAndSubmit(id, hasAccess) {
                this.clearRoomErrors();
                this.clearRoomPasswordError();

                this.joinId = id;
                if (hasAccess) {
                    this.submitJoin();
                } else {
                    this.indicateRoomHasPassword();
                }
            }
        }
    }
</script>
