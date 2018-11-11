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
            "csrfToken": String, 
        },

        data() {
            return {
                newMessage: '',        
            }
        },

        methods: {
            sendMessage:function() {
                this.newMessage = ''
            }
        }    
    }
</script>

<style lang="scss" scoped>
</style>