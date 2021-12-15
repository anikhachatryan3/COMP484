<template>
<div class="text-primary">
    <span class="m-2">
        <router-link to="/">Home</router-link>
    </span>
    <span v-if="user != null" class="m-2">
        <router-link to="/my-posts">My Posts</router-link>
    </span>
    <span v-if="user == null" class="m-2">
        <router-link to="/login">Login</router-link>
    </span>
    <span v-if="user == null" class="m-2">
        <router-link to="/register">Register</router-link>
    </span>
    <span v-if="user != null" class="m-2">
        <span @click="logout()"><u>Logout</u></span>
    </span>
</div>
</template>

<script>
export default {
    name: 'Navbar',
    data() {
        return {
            user: null
        }
    },
    mounted() {
        let self = this;
        self.user = self.$session.get('user');
        this.$bus.$on('login', function(){
            self.user = self.$session.get('user');
        });
    },
    methods: {
        logout() {
            this.$session.destroy();
            this.$router.push({ name: 'Login' });
            this.user = null;
        }
    }
}
</script>

<style scoped>
span {
    cursor: pointer;
}
</style>