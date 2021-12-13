<template>
  <div>
    <h1>My Posts</h1>
    <div>
      <form>
        <div>
          <input name="title" />
          <input name="body" />
        </div>
      </form>
    </div>
    <div v-for="post in posts" :key="post.id">
      <div>{{error}}</div>
      <div>{{post.title}}</div>
      <div>{{post.body}}</div>
      <div>{{post.user.username}}</div>
      <div>{{post.created_at}}</div>
      <div><button @click="deletePost(comment)">Delete</button></div>
      <hr>
      <div>
        <div v-for="comment in post.comments" :key="comment.id">
          <div>{{comment.body}}</div>
          <div>{{comment.user.username}}</div>
          <div>{{comment.created_at}}</div>
          <div><button @click="deleteComment(comment)">Delete</button></div>
        </div>
      </div>
      <div>
        <form>
          <div>
            <textarea v-model="comment.body"></textarea>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'MyPosts',
  data() {
    return {
      posts: [],
      comment: {
        body: '',
        user_id: '',
      }
    }
  },
  created() {
    let self = this;
    axios.get('http://localhost:8000/api/' + self.$session.user.id + '/posts')
    .then(function (response) {
        self.posts = response.data.posts;
        console.log(self.posts)
    }).catch(function (error) {
        self.error = error.response.data.errors;
    });
  },
  methods: {
    deletePost(post) {
      let self = this;
      axios.delete('http://localhost:8000/api/' + self.$session.user.id + '/posts/' + post.id)
      .then(function() {
        // self.response = response.data.message;
        for (let i = 0; i < self.posts.length; i++) {
          if (self.posts[i].id === post.id) {
            self.posts.splice(i, 1)
            break
          }
        }
      })
      .catch(function(error) {
        self.error = error.response.data.errors.other;
      })
    },
    // deleteComment(comment) {
    //   console.log('deleting comment');
    // }
  }
}
</script>

<style scoped>
</style>
