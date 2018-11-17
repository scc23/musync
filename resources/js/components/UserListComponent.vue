<template>
    <div class="user-list">
        <div class="user-list card">
            <div class="user-list card-header">Online Users
            </div>
            <div class="user-list card-body">
                    <div v-for="user in onlineUsers">
                            <p>{{user.name}}
                                <font-awesome-icon icon="volume-up" 
                                v-if="broadcasterName === user.name && broadcasterId == user.id"/>
                            </p>    
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "broadcasterName": String,
            "broadcasterId": String,
            "roomId": String,
        },

        data() {
            return {
                onlineUsers: []
            }
        },

        mounted() {
            Echo.join(`room-presence.${this.roomId}`)
                .here((users) => {;
                    this.onlineUsers = users;
                })
                .joining((user) => {
                    if (this.onlineUsers.indexOf(user) == -1) {
                        this.$emit("user-join", user.name);
                        this.onlineUsers.push(user);
                    }
                })
                .leaving((user) => {
                    var index = this.onlineUsers.indexOf(user);
                    if (index > -1) {
                        this.$emit("user-leave", user.name);
                        this.onlineUsers.splice(index, 1);
                    }
                });
        }
    }
</script>

<style lang="scss" scoped>
    .fa-volume-up {
        margin-left: 10px;
    }

    .card-body {
        overflow-y: scroll;
        height: 196px;
    }

    .user-list.card {
        margin-bottom: 20px;
    }

</style>
