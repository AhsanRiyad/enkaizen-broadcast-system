import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import './styles/tailwind.css';

Vue.config.productionTip = false

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: "1590530dcf8b3b74448e",
//     cluster: "ap2",
// });

import VueCookies from 'vue-cookies'
Vue.use(VueCookies);
VueCookies.config('7d');

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
