// ChatForm component displays an input field and a send button
<template>
    <div>
        Chat stuff here
        <div class="input-group">
            <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                    Send
                </button>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                });

                this.newMessage = ''
            }
        }    
    }
</script>