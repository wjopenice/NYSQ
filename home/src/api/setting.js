import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/setting/filter',
    method: 'get',
    params: query
  })
}

export function fetchSettingIndex(query) {
  return request({
    url: '/setting/index',
    method: 'get',
    params: query
  })
}

export function specialSubject(query) {
  return request({
    url: '/setting/thematic',
    method: 'get',
    params: query
  })
}

export function getNum(query) {
  return request({
    url: '/setting/gettotal',
    method: 'get',
    params: query
  })
}

export function getMercList(query) {
  return request({
    url: '/setting/mercadmin',
    method: 'get',
    params: query
  })
}

export function addMercData(query) {
  return request({
    url: '/setting/addmercadmin',
    method: 'get',
    params: query
  })
}
