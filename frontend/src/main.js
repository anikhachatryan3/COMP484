import Vue from 'vue'
import App from './App.vue'
import router from './router'
import Vuex from 'vuex'
import VueSession from 'vue-session'

Vue.use(Vuex);
Vue.use(VueSession);

const store = new Vuex.Store({
  state: {
    auth: false,
    user: {
      id: '',
      username: '',
      email: '',
      role: '',
    }
  },
  mutations: {
    logout (state) {
      state.auth = false
      state.user.id = ''
      state.user.username = ''
      state.user.email = ''
      state.user.role = ''
    },
    login (state, user) {
      state.auth = true
      state.user.id = user.id
      state.user.username = user.username
      state.user.email = user.email
      state.user.role = user.role
    },
  }  
});

Vue.config.productionTip = false

new Vue({
  router,
  components: { App },
  template: '<App/>',
  store: store,
  render: h => h(App),
}).$mount('#app')
