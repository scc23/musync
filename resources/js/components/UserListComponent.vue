<template>
    <div class="user-list">
        <div class="user-list card">
            <div class=" user-list card-header">Online Listeners
            </div>
            <div class="user-list card-body">  
                <div v-for="user in onlineUsers">
                    <transition name="fade">
                        <p>{{user.name}}  <font-awesome-icon icon="music" />
                        </p>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
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
                        this.onlineUsers.push(user);
                    }
                })
                .leaving((user) => {
                    var index = this.onlineUsers.indexOf(user);
                        if (index > -1) {
                            this.onlineUsers.splice(index, 1);
                        }
                });
        } 
    }
</script>

<style lang="scss" scoped>

    .card-body {
        overflow-y: scroll;  
        height: 150px;
    }

    .user-list.card {
        margin-bottom: 20px;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    
    .fade-enter, .fade-leave-to {
        opacity: 0;

    }
</style>