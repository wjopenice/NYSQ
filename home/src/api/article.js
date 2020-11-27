import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/index/list',
    method: 'post',
    params: query
  })
}

export function filterList(query) {
  return request({
    url: '/index/list',
    method: 'post',
    params: query
  })
}

export function fetchSettingIndex(query) {
  return request({
    url: '/setting/index',
    method: 'post',
    params: query
  })
}

export function fetchArticle(id) {
  return request({
    url: '/article/detail',
    method: 'get',
    params: { id }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/article/pv',
    method: 'get',
    params: { pv }
  })
}

export function createArticle(data) {
  return request({
    url: '/article/create',
    method: 'post',
    data
  })
}

export function updateArticle(data) {
  return request({
    url: '/article/update',
    method: 'post',
    data
  })
}

export function typeList(query) {
  return request({
    url: '/article/typelist',
    method: 'get',
    params: query
  })
}

export function list(query) {
  return request({
    url: '/article/index',
    method: 'get',
    params: query
  })
}

export function status(query) {
  return request({
    url: '/article/status',
    method: 'get',
    params: query
  })
}

export function statusFilter(query) {
  return request({
    url: '/article/filter',
    method: 'get',
    params: query
  })
}

export function statusRemove(query) {
  return request({
    url: '/article/remove',
    method: 'get',
    params: query
  })
}

export function thematic(query) {
  return request({
    url: '/article/specialsubject',
    method: 'get',
    params: query
  })
}

export function thematicAdd(query) {
  return request({
    url: '/article/insertspecial',
    method: 'get',
    params: query
  })
}

export function thematicDel(query) {
  return request({
    url: '/article/deletespecial',
    method: 'get',
    params: query
  })
}
