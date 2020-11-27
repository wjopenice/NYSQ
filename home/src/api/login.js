import request from '@/utils/request'

export function getlogin(data) {
  return request({
    url: '/login/index',
    method: 'post',
    data
  })
}
