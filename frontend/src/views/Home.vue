<template>
  <div>
    <h1 class="mt-3">Hello, {{user.username}}!</h1>
    <div v-for="post in posts" :key="post.id">
      <div>{{error}}</div>
      <h4>{{post.title}}</h4>
      <div><p>{{post.body}}</p></div>
      <div>{{post.user.username}}</div>
      <div>{{dateTime(post.created_at)}}</div>
      <div v-if="post.user.id == user.id || user.role.name == 'admin'"><button @click="deletePost(post)" type="button" class="btn btn-danger mt-2">Delete Post</button></div>
      <div class="mt-3">
        <h3>Comment(s):</h3>
        <div v-for="comment in post.comments" :key="comment.id">
          <div>{{comment.body}}</div>
          <div>{{comment.user.username}}</div>
          <div>{{dateTime(comment.created_at)}}</div>
          <div v-if="comment.user.id == user.id || user.role.name == 'admin'"><button @click="deleteComment(comment, post)" type="button" class="btn btn-danger mt-2">Delete Comment</button></div>
          <br>
        </div>
        <br>
      </div>
      <div>
        <form>
          <div>
            <div>Create a Comment:</div>
            <textarea v-model="comment.body" rows="5" cols="40" placeholder="Your comment here..."></textarea>
            <!-- <br> -->
            <div><button @click="createComment(post)" type="button" class="btn btn-success">Publish Comment</button></div>
            <br>
            <hr>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'Home',
  data() {
    return {
      posts: [],
      comment: {
        body: '',
        user_id: '',
      },
      error: '',
      title: '',
      body: '',
      user_id: ''
    }
  },
  created() {
    let self = this;
    axios.get('https://comp-484.herokuapp.com/api/posts')
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
  },
  methods: {
    dateTime(value) {
      return moment(String(value)).format('MM/DD/YYYY hh:mm a');
    },
    deletePost(post) {
      let self = this;
      // axios.delete('https://comp-484.herokuapp.com/api/' + self.$session.user.id + '/posts/' + post.id)
      axios.delete('https://comp-484.herokuapp.com/api/' + self.$session.get('user').id + '/posts/' + post.id)
      .then(function(response) {
        self.response = response.data.message;
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
    deleteComment(comment, post) {
      // console.log('deleting comment');
      axios.delete('https://comp-484.herokuapp.com/api/' + this.$session.get('user').id + '/comments/' + comment.id)
      .then(function () {
        for(let i = 0; i < post.comments.length; i++) {
          if(post.comments[i].id === comment.id) {
            post.comments.splice(i, 1);
            break;
          }
        }
      })
      .catch(function(error) {
        self.error = error.response.data.errors.other;
      })
    },
    createComment(post) {
      let self = this;
      axios.post('https://comp-484.herokuapp.com/api/create-comment', {
        body: this.comment.body,
        post_id: post.id,
        user_id: self.$session.get('user').id,
      }).then(function (response) {
        post.comments.push(response.data.comment);
        self.comment.body = '';
      }).catch(function (error) {
        self.error = error.response.data.errors.other;
      })
    }
  }
}
</script>

<style scoped>
</style>
