<template>
  <div>
    <h1 class="mt-3 mb-3">Register</h1>
    <form>
      <div>
        <input v-model="email" placeholder="Email" type="email" required />
      </div>
      <div class="mt-2">
        <input v-model="username" placeholder="Username" required />
      </div>
      <div class="mt-2">
        <input v-model="password" placeholder="Password" type="password" required />
      </div>
      <div class="mt-2">
        <input type="radio" name="admin" value="Admin" v-model="role">
        <label for="admin" style="padding-right: 10px;">Admin</label>
        <input type="radio" name="standard" value="Standard" checked v-model="role">
        <label for="standard">Standard</label>
      </div>
      <div class="mt-2">
        <button @click="register()" type="button">Register</button>
      </div>
      <div>
        {{ error }}
      </div>
      <br>
      <div>
        Already have an account?
        <router-link to="/login">Login Here</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Register',
  data() {
    return {
      email: '',
      username: '',
      password: '',
      role: '',
      error: '',
    }
  },
  methods: {
    register() {
      if(this.role == '') {
        this.error = 'Please select a role';
      }
      else {
        let self = this;
        axios.post('https://comp-484.herokuapp.com/api/register2', {
          email: this.email,
          username: this.username,
          role: this.role,
          password: this.password,
        }).then(function (response) {
          self.$session.start();
          self.session.set('auth', true);
          self.$session.set('user', response.data.user);
          // localStorage.setItem('auth', true);
          // localStorage.setItem('user', response.data.user);
          // self.$store.commit('login', response.data.user);
          self.$router.push('/');
        }).catch(function (error) {
          if(error.response.data.errors.email != null) {
            self.error = error.response.data.errors.email[0]
          }
          else if(error.response.data.errors.username != null) {
            self.error = error.response.data.errors.username[0]
          }
          else if(error.response.data.errors.role != null) {
            self.error = error.response.data.errors.role[0]
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
}
</script>

<style scoped>
</style>
