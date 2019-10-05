import Vue from 'vue'
import VueRouter from 'vue-router'
import Game from './pages/Game.vue'
Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        component:Game ,
    },
  
];

const router = new VueRouter({
    routes
});
export default router;