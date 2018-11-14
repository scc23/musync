<!-- ChatForm component -->
<!-- Displays an input field and a send button -->
<template>
    <div class="chat-form">
        <div class="input-group">
            <input id="btn-input" type="text" class="form-control form-control-sm" placeholder="Type a message..." v-model="newMessage" @keyup.enter="sendMessage">
            <span class="input-group-append">
                <button class="btn btn-secondary btn-sm" id="btn-chat" @click="sendMessage">Send</button>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "userName": String,
            "roomId": String
        },
        data() {
            return {
                newMessage: '',
            }
        },
        mounted() {
            $('#btn-chat').prop('disabled', true);
        },
        methods: {
            isValidMessage: function() {
                return (this.newMessage.trim().length != 0);
            },
            sendMessage: function() {
                if (this.isValidMessage()) {
                    var message = {
                        user: this.userName,
                        message: this.newMessage
                    }
                    Echo.private(`room.${this.roomId}`)
                        .whisper("MessageSent", message);
                    this.newMessage = '';

                    this.$emit("message-created", message);
                }
            }
        },
        watch: {
            newMessage: function() {
                if (this.isValidMessage()) {
                    $('#btn-chat').prop('disabled', false);
                } else {
                    $('#btn-chat').prop('disabled', true);
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>
