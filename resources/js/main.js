require('./bootstrap');
window.Vue = require('vue').default;

import Vue from 'vue'
import router from "@/router"
import vuetify from '@/config/vuetify'
import pinia from '@/config/pinia'
import * as components from '@/config/components'

import 'nprogress/nprogress.css'
import VueTheMask from 'vue-the-mask'

Vue.use(VueTheMask);
Vue.config.productionTip = false;
components.register();

new Vue({
    el: '#app',
    router,
    vuetify,
    components: {
        'App': () => import(/* webpackPreload: true */ './App')
    },
    pinia
});

axios.interceptors.request.use(request => {
    request.withCredentials = true;

    // Update token axios header
    if (store.getters['auth/TOKEN']) {
      request.headers.common['Authorization'] = 'Bearer ' + store.getters['auth/TOKEN'];
    }

    return request;
})

axios.interceptors.response.use(
	function (response) {
		return response;
	},
    
	function (error) {
		/* INTERCEPTS 401 UNAUTHENTICATED, EXPIRES SESSIONS and reloads page */
        if (error.response.status === 401 || error.response.status === 419) {
			// refresh page
			router.push('/guest')
		}
		return Promise.reject(error);
	}
)