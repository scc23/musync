<template>                            
    <div class="card">
        <div class="card-header">Chat</div>
        <div class="card-body">  
            <chat-messages-component v-bind:csrf-token="csrfToken">
            </chat-messages-component>      
        </div>

        <div class="card-footer">
            <chat-form-component v-bind:csrf-token="csrfToken">
            </chat-form-component>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            "csrfToken": String
        },

        components: {
            'chat-messages-component': require('./ChatMessagesComponent.vue'),
            'chat-form-component': require('./ChatFormComponent.vue'),
        },
    }
</script>

<style lang="scss" scoped>
    .card-body {
        overflow-y: scroll;  
        height: 300px;
    }
</style>