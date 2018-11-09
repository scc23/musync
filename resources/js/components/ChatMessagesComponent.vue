<!-- ChatMessages Component -->
<!-- Accepts an array of messages as props, loops through them and displays the name of the user who sent the message and the message body -->

<template>
    <div class="chat-messages">
        <ul class="chat">
            <li class="left clearfix" v-for="message in messages">
                <div class="chat-body clearfix">
                    <div class="header">
                        <strong class="primary-font">
                            {{message.name}}
                        </strong> 
                        <p>
                            {{message.message}}
                        </p>
                    </div>
                </div>
            </li> 
        </ul> 
    </div>
</template>

<script>
    export default {
        props: ['messages'],
    }
</script>

