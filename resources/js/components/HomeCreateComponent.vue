<template>
    <div>
        <form method="POST" @submit.prevent="submitCreate">
            <input type="hidden" name="_token" :value="csrfToken">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 form-group">
                    <label for="create-room-name" class="mb-1 control-label">Room Name</label>
                    <input type="text" name="create-room-name" id="create-room-name" class="form-control"
                        placeholder="Room Name" v-model="createName" @change="clearRoomNameError">
                    <span class="help-block">{{createNameError}}</span>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 form-group">
                    <label for="create-room-password" class="mb-1">Password</label>
                    <input type="password" name="create-room-password" id="create-room-password" class="form-control"
                        placeholder="Password" v-model="createPassword" :disabled="!isPrivate" @change="clearRoomPasswordError">
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
                <button type="button" class="home-btn btn btn-primary btn-block" @click="returnLanding">
                Back
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "csrfToken": String,
        },
        data() {
            return {
                createName: "",
                createNameError: "",
                createPassword: "",
                createPasswordError: "",
                isPrivate: false,
            };
        },
        methods: {
            resetState() {
                this.createName = ""
                this.createNameError = ""
                this.createPassword = ""
                this.createPasswordError = ""
                this.isPrivate = false
            },
            returnLanding(event) {
                this.$emit("set-body-component", "home-landing-component");
                this.resetState();
            },
            clearPassword() {
                // Condition run when isPrivate = true because @click
                // is processed before isPrivate is set.
                if (this.isPrivate) {
                    this.createPassword = "";
                    this.clearRoomPasswordError();
                }
            },
            clearRoomNameError() {
                this.createNameError = "";
            },
            clearRoomPasswordError() {
                this.createPasswordError = "";
            },
            validateInput() {
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
            submitCreate() {
                if (this.validateInput()) {
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
            }
        }
    }
</script>
