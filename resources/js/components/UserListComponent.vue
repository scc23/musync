<template>
    <div class="user-list">
        <div class="card">
            <div class="card-header">Online Listeners
            </div>
            <div class="card-body">  
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "csrfToken": String
        },
        mounted() {
            console.log('User list Component mounted.');
        }
    }
</script>

<style lang="scss" scoped>
</style>