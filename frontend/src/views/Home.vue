<template>
  <div>
    <h1>All Posts</h1>
    <div v-for="post in posts" :key="post.id">
      <div>{{post.title}}</div>
      <div>{{post.body}}</div>
      <div>{{post.user.username}}</div>
      <div>{{post.created_at}}</div>
      <hr>
      <div>
        <div v-for="comment in post.comments" :key="comment.id">
          <div>{{comment.body}}</div>
          <div>{{comment.user.username}}</div>
          <div>{{comment.created_at}}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Home',
  data() {
    return {
      posts: []
    }
  },
  created() {
    let self = this;
    axios.get('http://localhost:8000/api/posts')
    .then(function (response) {
        self.posts = response.data.posts;
    }).catch(function (error) {
        self.error = error.response.data.errors;
    });
  },
  computed: {
    user() {
      return this.$session.get('user');
    }
  }
}
</script>

<style scoped>
</style>
