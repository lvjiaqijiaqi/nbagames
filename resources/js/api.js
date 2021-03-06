import axios from 'axios' 
import * as types from './store/types'
import store from './store/store'
const host = '/api'
//const host = 'http://lvjiaqi.cn/api'

// 普通请求
const request = async (options) => {
  if (typeof options === 'string') {
    options = {
      url: options
    }
  }
  options.url = host + '/' + options.url
  const instance = axios.create(options);
  let response = await instance.request()
  return response
}

// 带身份认证的请求
const authRequest = async (options) => {
  if (typeof options === 'string') {
    options = {
      url: options
    }
  }
  let accessToken = await getToken()
  let headers = options.headers || {}
  headers.Authorization = 'Bearer ' + accessToken
  options.headers = headers
  return request(options)
}

const refreshToken = async (accessToken) => {
  // 请求刷新接口
  let options = {
    url: 'authorizations/current',
    method: 'PUT',
    headers: {
      'Authorization': 'Bearer ' + accessToken
    }
  };
  let refreshResponse = await request(options)
  // 刷新成功状态码为 200
  if (refreshResponse.status === 200) {
    // 将 Token 及过期时间保存在 storage 中
    localStorage.setItem('access_token', refreshResponse.data.access_token)
    localStorage.setItem('access_token_expired_at', new Date().getTime() + refreshResponse.data.expires_in * 1000)
  }

  return refreshResponse
}

// 获取 Token
const getToken = async (options) => {
  // 从缓存中取出 Token
  let accessToken = localStorage.getItem('access_token')
  let expiredAt = localStorage.getItem('access_token_expired_at')
  // 如果 token 过期了，则调用刷新方法
  if (accessToken && new Date().getTime() > expiredAt) {
    let refreshResponse = await refreshToken(accessToken)

    // 刷新成功
    if (refreshResponse.statusCode === 200) {
      accessToken = refreshResponse.data.access_token
    } else {
      // 刷新失败了，重新调用登录方法，设置 Token
      let authResponse = await login()
      if (authResponse.statusCode === 201) {
        accessToken = authResponse.data.access_token
      }
    }
  }
  return accessToken
}

// 登录
const login = async (params) => {

  // 接口请求 weapp/authorizations
  let authResponse = await request({
    url: 'authorizations',
    data: params,
    method: 'POST'
  })

  // 登录成功，记录 tosken 信息
  if (authResponse.status === 200) {
     store.commit(types.LOGIN, authResponse.data)
  }

  return authResponse
}

export default {
  request,
  authRequest,
  login
}
