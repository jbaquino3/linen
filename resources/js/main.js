require('./bootstrap');
window.Vue = require('vue').default;

import Vue from 'vue';
import router from "./router";
import 'vuetify/dist/vuetify.min.css';
import Vuetify from 'vuetify/lib';
import 'nprogress/nprogress.css';
import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);
Vue.use(Vuetify);

Vue.config.productionTip = false;

// Register components
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'

const requireComponent = require.context( './components', true, /[A-Z]\w+\.(vue|js)$/)
  
requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName)
    const componentName = upperFirst( camelCase( fileName.split('/').pop().replace(/\.\w+$/, '')))
    Vue.component( componentName, componentConfig.default )
})

new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdiSvg',
        }
    }),
    components: {
        'App': () => import(/* webpackPreload: true */ './App')
    }
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