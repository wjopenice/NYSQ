import request from '@/utils/request'

export function fetchType(query) {
  return request({
    url: '/businessdistrict/type',
    method: 'get',
    params: query
  })
}

export function createArticle(query) {
  return request({
    url: '/businessdistrict/adddb',
    method: 'get',
    params: query
  })
}

export function statusFilter(query) {
  return request({
    url: '/businessdistrict/remove',
    method: 'get',
    params: query
  })
}

export function status(query) {
  return request({
    url: '/businessdistrict/list',
    method: 'get',
    params: query
  })
}
