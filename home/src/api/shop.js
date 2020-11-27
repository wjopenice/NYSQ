import request from '@/utils/request'

export function getShopList(query) {
  return request({
    url: '/shop/shoplist',
    method: 'get',
    params: query
  })
}

export function statusShopSort(query) {
  return request({
    url: '/shop/shopsort',
    method: 'get',
    params: query
  })
}

export function statusShopSwitch(query) {
  return request({
    url: '/shop/shopswitch',
    method: 'get',
    params: query
  })
}

export function removeShop(query) {
  return request({
    url: '/shop/remove',
    method: 'get',
    params: query
  })
}

export function createArticle(query) {
  return request({
    url: '/shop/shopadd',
    method: 'get',
    params: query
  })
}

export function selectArticle(query) {
  return request({
    url: '/shop/shopfind',
    method: 'get',
    params: query
  })
}

export function updateArticle(query) {
  return request({
    url: '/shop/shopedit',
    method: 'get',
    params: query
  })
}
