<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" v-text="header"></div>
                    <div class="card-body">
                        <div csrf-token="csrfToken" access-token="accessToken"
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
            'landing': require('./HomeLandingComponent'),
            'create': require('./HomeCreateComponent'),
            'join': require('./HomeJoinComponent')
        },
        props: {
            "csrfToken": String,
            "accessToken": String
        },
        data() {
            return {
                header: "Dashboard",
                bodyComponent: "landing"
            }
        },
        mounted() {
            axios.defaults.headers.common["Authorization"] = "Bearer " + this.accessToken;
        },
        methods: {
            setBodyComponent(component) {
                this.bodyComponent = component;
                if (component == "landing") {
                    this.header = "Dashboard"
                } else if (component == "create") {
                    this.header = "Create Room"
                } else if (component == "join") {
                    this.header = "Join Room"
                }
            }
        }
    }
</script>
