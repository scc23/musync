<!-- ChatMessages Component -->
<!-- Accepts an array of messages as props, loops through them and displays the name of the user who sent the message and the message body -->

<template>
    <div class="chat-messages">
        <ul class="chat">
            <!-- <li class="left clearfix" v-for="message in dummyMessages">
                <div class="chat-body clearfix">
                    <div class="header">
                        <strong class="primary-font">
                            {{message.user}}
                        </strong> 
                    </div>
                    <p>
                        {{message.message}}
                    </p>
                </div>
            </li> -->
            <li class="left clearfix" v-for="message in messages">
                <p> {{message.id}} {{message.user_id}} {{message.room_id}} {{message.message}}</p>
            </li>
        </ul> 
    </div>
</template>

<script>
    export default {

        // Dummy messages for display
        //name: 'dummyMessages',
        props: ['messages'],
        /*
        data(){
            return {
                dummyMessages: [
                    {
                        user: 'John',
                        message: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
                    },
                    {
                        user: 'Paul',
                        message: 'Curabitur bibendum ornaredolor, quis ullamcorper ligula sodales.'
                    },
                    {
                        user: 'John',
                        message: 'Curabitur bibendum ornaredolor, quis ullamcorper ligula sodales.'
                    },
                    {
                        user: 'John',
                        message: 'Curabitur bibendum ornaredolor, quis ullamcorper ligula sodales.'
                    },
                ]
            }
        } */
    }
</script>

