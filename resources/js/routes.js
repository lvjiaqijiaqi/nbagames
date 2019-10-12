import Vue from 'vue'
import VueRouter from 'vue-router'
import Game from './pages/Game.vue'
import App from './pages/App.vue'
import Login from './pages/login.vue'

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'app',
        component:App ,
        children: [
        { path: '', component: Game }
      ]
    }
];

const router = new VueRouter({
    routes
});
export default router;