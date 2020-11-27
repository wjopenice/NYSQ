import request from '@/utils/request'

export function list(query) {
  return request({
    url: '/merc/index',
    method: 'get',
    params: query
  })
}

export function selectfloor(query) {
  return request({
    url: '/merc/selectfloor',
    method: 'get',
    params: query
  })
}

export function getList(query) {
  return request({
    url: '/merc/tag',
    method: 'get',
    params: query
  })
}

export function status(query) {
  return request({
    url: '/merc/status',
    method: 'get',
    params: query
  })
}

export function statusFilter(query) {
  return request({
    url: '/merc/filter',
    method: 'get',
    params: query
  })
}

export function statusRemove(query) {
  return request({
    url: '/merc/remove',
    method: 'get',
    params: query
  })
}

export function removeMerc(query) {
  return request({
    url: '/merc/removemerc',
    method: 'get',
    params: query
  })
}

export function saveList(query) {
  return request({
    url: '/merc/savetag',
    method: 'get',
    params: query
  })
}

export function insertList(data) {
  return request({
    url: '/merc/insertmerc',
    method: 'post',
    data
  })
}

export function findList(query) {
  return request({
    url: '/merc/findlist',
    method: 'post',
    params: query
  })
}

export function updateList(data) {
  return request({
    url: '/merc/updatemerc',
    method: 'post',
    data
  })
}
