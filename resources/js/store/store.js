import Vuex from 'vuex'
import Vue from 'vue'
import * as types from './types'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: {},
        token: localStorage.getItem("access_token")
    },
    mutations: {
        [types.LOGIN]: (state, data) => {
            localStorage.setItem('access_token', data.access_token)
            localStorage.setItem('access_token_expired_at', new Date().getTime() + data.expires_in * 1000)
            state.token = data.access_token
        },
        [types.LOGOUT]: (state) => {
            localStorage.removeItem('access_token')
            localStorage.removeItem('access_token_expired_at');
            state.token = null
        }
    }
})