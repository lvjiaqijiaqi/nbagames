import axios from 'axios'

const host = 'http://nbagames.test/api'


// 普通请求
const request = async (url , options) => {
  const instance = axios.create(options);
  let response = await axios.get(host + '/' + url)
  return response
}

// 带身份认证的请求
const authRequest = async (url , options) => {
  let accessToken = await getToken()
  let header = options.header || {}
  header.Authorization = 'Bearer ' + accessToken
  options.header = header
  return request(url, options)
}

const refreshToken = async (accessToken) => {
  // 请求刷新接口
  let options = {
    url: host + '/' + 'authorizations/current',
    method: 'PUT',
    header: {
      'Authorization': 'Bearer ' + accessToken
    }
  };
  let refreshResponse = await request(host + '/' + 'authorizations/current' , options)

  // 刷新成功状态码为 200
  if (refreshResponse.statusCode === 200) {
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

export default {
  request,
  authRequest
}