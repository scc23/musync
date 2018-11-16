<template>
    <div>
        <div class="row justify-content-center mb-2">
            <div class="col-12 col-sm-6">
                <button class="home-btn btn btn-primary btn-block" @click="promptCreate">
                    Create Room
                </button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-6">
                <button type="button" class="home-btn btn btn-primary btn-block" @click="promptJoin">
                    Join Room
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
        methods: {
            promptCreate() {
                this.$emit("set-body-component", "home-create-component");
            },
            promptJoin() {
                this.$emit("set-body-component", "home-join-component");
            }
        }
    }
</script>
