import Vue from "vue";
import VueRouter from "vue-router";
import NProgress from 'nprogress'

import routes from './routes'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
        return savedPosition;
        } else {
        return { x: 0, y: 0 };
        }
    },
});

router.beforeEach((to,From, next) => {
    NProgress.start();
    next();
});

router.afterEach(() => {  
    NProgress.done();
});

export default router;