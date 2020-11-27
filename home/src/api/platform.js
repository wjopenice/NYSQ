import request from '@/utils/request'

export function list(query) {
  return request({
    url: '/platform/getlist',
    method: 'post',
    params: query
  })
}

export function getRoles(query) {
  return request({
    url: '/platform/roles',
    method: 'post',
    params: query
  })
}

export function addRoles(query) {
  return request({
    url: '/platform/addroles',
    method: 'post',
    params: query
  })
}

