<template>
    <div class="user-list">
        <h2>Users</h2>
        <ul >
            <li v-for="user in users">
                <p>{{ user.name }}</p>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['users'],
        mounted() {
            console.log('User list Component mounted.');
        }
    }
</script>