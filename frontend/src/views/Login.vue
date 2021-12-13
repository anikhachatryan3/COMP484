<template>
    <div>
        <h1>Login</h1>
        <form>
            <div>
                <input v-model="email" placeholder="Email" type="email" required />
            </div>
            <div>
                <input v-model="password" placeholder="Password" type="password" required />
            </div>
            <div>
                <button @click="login()">Login</button>
            </div>
            <div class="errorMessage">
                {{ error }}
            </div>
            <div>
                Don't have an account?
                <router-link to="/register">Register Here</router-link>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
        email: '',
        password: '',
        error: '',
    }
  },
  methods: {
    login() {
        let self = this;
        axios.post('http://localhost:8000/api/login', {
            email: this.email,
            password: this.password,
        }).then(function (response) {
            self.$session.start();
            self.$session.set('auth', true);
            self.$session.set('user', response.data.user);
            self.$store.commit('login', response.data.user);
            self.$router.push('/home');
        }).catch(function (error) {
            console.log(error.responde.data)
            if(error.response.data.errors.email != null) {
                self.error = error.response.data.errors.email[0]
            }
            else if(error.response.data.errors.password != null) {
                self.error = error.response.data.errors.password[0]
            }
            else if(error.response.data.errors.other) {
                self.error = error.response.data.errors.other
            }
        });
    }
  }
}
</script>

<style scoped>
    .errorMessage {
        color: red;
    }
</style>
