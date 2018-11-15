<template>
    <div class="card">
        <div class="card-header">Chat</div>
        <div class="card-body">
            <chat-messages-component v-bind:room-id="roomId"
                                     v-bind:messages="messages">
            </chat-messages-component>
        </div>

        <div class="card-footer">
            <chat-form-component v-bind:room-id="roomId">
            </chat-form-component>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "roomId": String
        },
        data() {
            return {
                messages: []
            };
        },
        components: {
            'chat-messages-component': require('./ChatMessagesComponent.vue'),
            'chat-form-component': require('./ChatFormComponent.vue'),
        },
        created() {
            Echo.private(`room.${this.roomId}`)
                .listen("MessageSent", (message) => {
                    this.messages.push(message);
                });
        },
    }
</script>

<style lang="scss" scoped>
    .card-body {
        overflow-y: scroll;
        height: 300px;
    }
</style>
