import request from '@/utils/request'

export function getTagList(query) {
  return request({
    url: '/tag/list',
    method: 'get',
    params: query
  })
}

export function status(query) {
  return request({
    url: '/tag/status',
    method: 'get',
    params: query
  })
}

export function statusRemove(query) {
  return request({
    url: '/tag/remove',
    method: 'get',
    params: query
  })
}

export function saveList(query) {
  return request({
    url: '/tag/savetag',
    method: 'get',
    params: query
  })
}

export function createTag(query) {
  return request({
    url: '/tag/inserttag',
    method: 'get',
    params: query
  })
}
