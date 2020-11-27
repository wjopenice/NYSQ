import request from '@/utils/request'
export function login(data) {
  return request({
    url: '/login/index',
    method: 'post',
    data
  })
}

export function logout(data) {
  return request({
    url: '/login/logout',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/user/info',
    method: 'get',
    params: { token }
  })
}



export function edit(data) {
  return request({
    url: '/user/edit',
    method: 'post',
    data
  })
}

export function list(data) {
  return request({
    url: '/user/list',
    method: 'get',
    params: data
  })
}

export function del(data) {
  return request({
    url: '/user/del',
    method: 'get',
    params: data
  })
}
























