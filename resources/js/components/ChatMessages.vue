// This component accepts an array of messages as props, loops through them and 
// displays the name of the user who sent the message and the message body

<template>
    <div>
        ChatMessages
        <ul class="chat">
            <li class="left clearfix" v-for="message in messages">
                <div class="chat-body clearfix">
                    <div class="header">
                        <strong class="primary-font">
                            {{ message.user.name }}
                        </strong>
                    </div>
                    <p>
                        {{ message.message }}
                    </p>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
  export default {
    props: ['messages']
  };
</script>

