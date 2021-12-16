<template>
  <div>
    <h1 class="mt-3">My Posts</h1>
    <div>
      <form>
        <div>
          <label>Title: </label>
          <br>
          <input v-model="title" />
          <br>
          <label class="mt-2">Body: </label>
          <br>
          <textarea v-model="body" rows="5" cols="30" class="mt-2"></textarea>
          <br>
          <!-- <input v-model="user_id" hidden> -->
          <button @click="createPost()" type="button" class="btn btn-success">Publish Post</button>
          <br>
          <hr>
        </div>
      </form>
    </div>
    <div class="mt-3" v-for="post in posts" :key="post.id">
      <h3>Post:</h3>
      <div>{{error}}</div>
      <h4>{{post.title}}</h4>
      <div>{{post.body}}</div>
      <div>{{post.user.username}}</div>
      <div>{{dateTime(post.created_at)}}</div>
      <div><button @click="deletePost(post)" type="button" class="btn btn-danger mt-2">Delete Post</button></div>
      <div class="mt-3">
        <h3>Comment(s):</h3>
        <div v-for="comment in post.comments" :key="comment.id">
          <div>{{comment.body}}</div>
          <div>{{comment.user.username}}</div>
          <div>{{dateTime(comment.created_at)}}</div>
          <div v-if="comment.user.id == user.id || user.role.name == 'admin'"><button @click="deleteComment(comment, post)" type="button" class="btn btn-danger mt-2">Delete Comment</button></div>
          <br>
        </div>
      </div>
      <br>
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
  name: 'MyPosts',
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
    // axios.get('https://comp-484.herokuapp.com/api/' + self.$session.user.id + '/posts')
    axios.get('https://comp-484.herokuapp.com/api/' + self.$session.get('user').id + '/posts')
    .then(function (response) {
        self.posts = response.data.posts;
        // console.log(self.posts)
    }).catch(function (error) {
        self.error = error.response.data.errors;
    });
    this.user_id = self.$session.get('user').id
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
      axios.delete('https://comp-484.herokuapp.com/api/' + this.$session.get('user').id + '/posts/' + post.id)
      .then(function () {
        for (let i = 0; i < self.posts.length; i++) {
          if (self.posts[i].id === post.id) {
            self.posts.splice(i, 1);
            break;
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
    createPost() {
      let self = this;
      axios.post('https://comp-484.herokuapp.com/api/create-post', {
        title: this.title,
        body: this.body,
        user_id: self.$session.get('user').id,
      }).then(function (response) {
        self.posts.push(response.data.post);
        self.title = null;
        self.body = null;
        // console.log(response.data);
      }).catch(function (error) {
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
