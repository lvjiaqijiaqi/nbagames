import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store/store'
import * as types from './store/types'
import App from './pages/App.vue'
import Login from './pages/login.vue'
import Home from './pages/home.vue'
import Index from './pages/Index.vue'

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'app',
        meta: {
            requireAuth: false,
        },
        component:App ,
        children: [
            {
                path: '/home',
                name: 'home',
                meta: {
                    requireAuth: true,
                },
                component:Home 
            },
            {
                path: '',
                name: 'Index',
                meta: {
                    requireAuth: true,
                },
                component:Index 
            }
        ]
    },
    {
        path: '/login',
        name: 'login',
        component:Login 
    }
];


const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(r => r.meta.requireAuth)) {
        console.log(store.state.token);
        if (store.state.token) {
            next();
        }
        else {
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            })
        }
    }
    else {
        next();
    }
})

export default router;