import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/operator/list',
    method: 'get',
    params: query
  })
}

export function add(query) {
  return request({
    url: '/operator/add',
    method: 'get',
    params: query
  })
}

export function getTagInfo(query) {
  return request({
    url: '/operator/getTagInfo',
    method: 'get',
    params: query
  })
}

export function del(query) {
  return request({
    url: '/operator/del',
    method: 'get',
    params: query
  })
}

export function getInfo(query) {
  return request({
    url: '/operator/getInfo',
    method: 'get',
    params: query
  })
}

export function getBusinessDistr(query) {
  return request({
    url: '/businessdistrict/getListByOpid',
    method: 'get',
    params: query
  })
}
