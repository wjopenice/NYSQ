import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/admin',
    component: Layout,
    redirect: '/AdminIndex',
    children: [{
      path: 'index',
      name: 'AdminIndex',
      component: () => import('@/views/admin/index'),
      meta: { title: '首页', icon: 'shouye' }
    }]
  },

  {
    path: '/user',
    component: Layout,
    redirect: '/User',
    name: 'User',
    meta: { title: '用户', icon: 'user' },
    children: [
      {
        path: 'management',
        name: 'UserManagement',
        component: () => import('@/views/user/management'),
        meta: { title: '管理组', icon: 'user' },
        children: [
          {
            path: 'index',
            name: 'UserManagementIndex',
            component: () => import('@/views/user/management/index'),
            meta: { title: '用户管理', icon: 'user' }
          },
          {
            path: 'roles',
            name: 'UserManagementRoles',
            component: () => import('@/views/user/management/roles'),
            meta: { title: '角色管理', icon: 'user' }
          },
          {
            path: 'authority',
            name: 'UserManagementAuthority',
            component: () => import('@/views/user/management/authority'),
            meta: { title: '权限管理', icon: 'user' }
          }
        ]
      },
      {
        path: 'platform',
        name: 'UserPlatform',
        component: () => import('@/views/user/platform'),
        meta: { title: '平台组', icon: 'user' },
        children: [
          {
            path: 'userlist',
            name: 'UserPlatformUserlist',
            component: () => import('@/views/user/platform/userlist'),
            meta: { title: '平台用户', icon: 'user' }
          },
          {
            path: 'userlog',
            name: 'UserPlatformUserlog',
            component: () => import('@/views/user/platform/userlog'),
            meta: { title: '平台日志', icon: 'user' }
          }
        ]
      }
    ]
  },

  {
    path: '/article',
    component: Layout,
    redirect: '/Article',
    name: 'Article',
    meta: { title: '内容', icon: 'neirongguanli' },
    children: [
      {
        path: 'type',
        name: 'ArticleType',
        component: () => import('@/views/article/type'),
        meta: { title: '布局管理', icon: 'neirongguanli' }
      },
      {
        path: 'index',
        name: 'ArticleIndex',
        component: () => import('@/views/article/index'),
        meta: { title: '文章管理', icon: 'neirongguanli' }
      },
      {
        path: 'verify',
        name: 'ArticleVerify',
        component: () => import('@/views/article/verify'),
        meta: { title: '文章审核', icon: 'neirongguanli' },
        children: [
          {
            path: 'apply',
            name: 'ArticleApply',
            component: () => import('@/views/article/verify/apply'),
            meta: { title: '申请文章', icon: 'neirongguanli' }
          },
          {
            path: 'success',
            name: 'ArticleVerifySuccess',
            component: () => import('@/views/article/verify/success'),
            meta: { title: '通过文章', icon: 'neirongguanli' }
          },
          {
            path: 'error',
            name: 'ArticleVerifyError',
            component: () => import('@/views/article/verify/error'),
            meta: { title: '驳回文章', icon: 'neirongguanli' }
          },
          {
            path: 'recover',
            name: 'ArticleVerifyRecover',
            component: () => import('@/views/article/verify/recover'),
            meta: { title: '回收文章', icon: 'neirongguanli' }
          }
        ]
      },
      {
        path: 'import',
        name: 'ArticleImport',
        component: () => import('@/views/article/import'),
        meta: { title: '专题管理', icon: 'neirongguanli' }
      }
    ]
  },

  {
    path: '/merc',
    component: Layout,
    redirect: '/Merc',
    name: 'Merc',
    meta: { title: '店铺', icon: 'dianpu' },
    children: [
      {
        path: 'index',
        name: 'MercIndex',
        component: () => import('@/views/merc/index'),
        meta: { title: '店铺管理', icon: 'dianpu' },
        children: [
          {
            path: 'list',
            name: 'MercIndexList',
            component: () => import('@/views/merc/index/list'),
            meta: { title: '店铺列表', icon: 'dianpu' }
          },
          {
            path: 'save',
            name: 'MercIndexSave',
            component: () => import('@/views/merc/index/save'),
            meta: { title: '添加店铺', icon: 'dianpu' }
          }
        ]
      },
      {
        path: 'verify',
        name: 'MercVerify',
        component: () => import('@/views/merc/verify'),
        meta: { title: '店铺审核', icon: 'dianpu' },
        children: [
          {
            path: 'apply',
            name: 'MercVerifyApply',
            component: () => import('@/views/merc/verify/apply'),
            meta: { title: '申请商铺', icon: 'dianpu' }
          },
          {
            path: 'success',
            name: 'MercVerifySuccess',
            component: () => import('@/views/merc/verify/success'),
            meta: { title: '通过商铺', icon: 'dianpu' }
          },
          {
            path: 'error',
            name: 'MercVerifyError',
            component: () => import('@/views/merc/verify/error'),
            meta: { title: '驳回商铺', icon: 'dianpu' }
          },
          {
            path: 'recover',
            name: 'MercVerifyRecover',
            component: () => import('@/views/merc/verify/recover'),
            meta: { title: '回收商铺', icon: 'dianpu' }
          }
        ]
      },
      {
        path: 'article',
        name: 'MercArticle',
        component: () => import('@/views/merc/article'),
        meta: { title: '内容创作', icon: 'dianpu' },
        children: [
          {
            path: 'material',
            name: 'MercArticleMaterial',
            component: () => import('@/views/merc/article/material'),
            meta: { title: '素材管理', icon: 'dianpu' }
          },
          {
            path: 'diystyle',
            name: 'MercArticleDiystyle',
            component: () => import('@/views/merc/article/diystyle'),
            meta: { title: 'DIY店铺', icon: 'dianpu' }
          }
        ]
      },
      {
        path: 'management',
        name: 'MercManagement',
        component: () => import('@/views/merc/management'),
        meta: { title: '店铺主题', icon: 'dianpu' },
        children: [
          {
            path: 'mercview',
            name: 'MercManagementMercview',
            component: () => import('@/views/merc/management/mercview'),
            meta: { title: '商铺模板', icon: 'dianpu' }
          },
          {
            path: 'mercstyle',
            name: 'MercManagementMercstyle',
            component: () => import('@/views/merc/management/mercstyle'),
            meta: { title: '店铺风格', icon: 'dianpu' }
          }
        ]
      },
      {
        path: 'tag',
        name: 'MercTag',
        component: () => import('@/views/merc/tag'),
        meta: { title: '店铺标签', icon: 'dianpu' }
      }
    ]
  },

  {
    path: '/shop',
    component: Layout,
    redirect: '/Shop',
    name: 'Shop',
    meta: { title: '商品', icon: 'theme' },
    children: [
      {
        path: 'index',
        name: 'ShopIndex',
        component: () => import('@/views/shop/index'),
        meta: { title: '商品管理', icon: 'theme' }
      },
      {
        path: 'tag',
        name: 'ShopTag',
        component: () => import('@/views/shop/tag'),
        meta: { title: '商品标签', icon: 'theme' }
      },
      {
        path: 'type',
        name: 'ShopType',
        component: () => import('@/views/shop/type'),
        meta: { title: '商品类型', icon: 'theme' }
      },
      {
        path: 'spec',
        name: 'ShopSpec',
        component: () => import('@/views/shop/spec'),
        meta: { title: '商品规格', icon: 'theme' }
      },
      {
        path: 'order',
        name: 'ShopOrder',
        component: () => import('@/views/shop/order'),
        meta: { title: '订单管理', icon: 'theme' }
      }
    ]
  },

  {
    path: '/operation',
    component: Layout,
    redirect: '/Operation',
    name: 'Operation',
    meta: { title: '运营', icon: 'yunying' },
    children: [
      {
        path: 'banner',
        name: 'OperationBanner',
        component: () => import('@/views/operation/banner'),
        meta: { title: '首页轮播', icon: 'yunying' }
      },
      {
        path: 'index',
        name: 'OperationIndex',
        component: () => import('@/views/operation/index'),
        meta: { title: '广告', icon: 'yunying' }
      },
      {
        path: 'activity',
        name: 'OperationActivity',
        component: () => import('@/views/operation/activity'),
        meta: { title: '活动', icon: 'yunying' }
      },
      {
        path: 'coupon',
        name: 'OperationCoupon',
        component: () => import('@/views/operation/coupon'),
        meta: { title: '优惠劵', icon: 'yunying' }
      },
      {
        path: 'integral',
        name: 'OperationIntegral',
        component: () => import('@/views/operation/integral'),
        meta: { title: '积分', icon: 'yunying' }
      },
      {
        path: 'sign',
        name: 'OperationSign',
        component: () => import('@/views/operation/sign'),
        meta: { title: '签到模块', icon: 'yunying' }
      }
    ]
  },

  {
    path: '/promote',
    component: Layout,
    redirect: '/Promote',
    name: 'Promote',
    meta: { title: '渠道', icon: 'qudao' },
    children: [
      {
        path: 'operator',
        name: 'operator',
        component: () => import('@/views/promote/operator'),
        meta: { title: '运营商管理', icon: 'qudao' },
        children: [
          {
            path: 'list',
            name: 'operatorList',
            component: () => import('@/views/promote/operator/list'),
            meta: { title: '运营商列表', icon: 'qudao' }
          },
          {
            path: 'edit',
            name: 'operatorEdit',
            component: () => import('@/views/promote/operator/edit'),
            meta: { title: '添加运营商', icon: 'qudao' }
          }]
      },
      {
        path: 'type',
        name: 'PromoteType',
        component: () => import('@/views/promote/type'),
        meta: { title: '商圈分类', icon: 'qudao' }
      },
      {
        path: 'index',
        name: 'PromoteIndex',
        component: () => import('@/views/promote/index'),
        meta: { title: '创建商圈', icon: 'qudao' }
      },
      {
        path: 'tag',
        name: 'PromoteTag',
        component: () => import('@/views/promote/tag'),
        meta: { title: '系统标签', icon: 'qudao' }
      }
    ]
  },

  {
    path: '/card',
    component: Layout,
    redirect: '/Card',
    name: 'Card',
    meta: { title: '圈子', icon: 'quanziguanli' },
    children: [
      {
        path: 'type',
        name: 'CardType',
        component: () => import('@/views/card/type'),
        meta: { title: '圈子分类', icon: 'quanziguanli' }
      },
      {
        path: 'index',
        name: 'CardIndex',
        component: () => import('@/views/card/index'),
        meta: { title: '圈子内容', icon: 'quanziguanli' }
      }
    ]
  },

  {
    path: '/setting',
    component: Layout,
    redirect: '/Setting',
    name: 'Setting',
    meta: { title: '设置', icon: 'shezhi' },
    children: [
      {
        path: 'index',
        name: 'SettingIndex',
        component: () => import('@/views/setting/index'),
        meta: { title: '常规设置', icon: 'shezhi' }
      },
      {
        path: 'thematic',
        name: 'SettingThematic',
        component: () => import('@/views/setting/thematic'),
        meta: { title: '首页ICON专区', icon: 'shezhi' }
      },
      {
        path: 'mercadmin',
        name: 'SettingMercadmin',
        component: () => import('@/views/setting/mercadmin'),
        meta: { title: '商家管理ICON专区', icon: 'shezhi' }
      },
      {
        path: 'backup',
        name: 'SettingBackup',
        component: () => import('@/views/setting/backup'),
        meta: { title: '数据备份', icon: 'shezhi' }
      },
      {
        path: 'extension',
        name: 'SettingExtension',
        component: () => import('@/views/setting/extension'),
        meta: { title: '第三方扩展', icon: 'shezhi' }
      },
      {
        path: 'clearup',
        name: 'SettingClearup',
        component: () => import('@/views/setting/clearup'),
        meta: { title: '过期清理', icon: 'shezhi' }
      },
      {
        path: 'sensitive',
        name: 'SettingSensitive',
        component: () => import('@/views/setting/sensitive'),
        meta: { title: '敏感词管理', icon: 'shezhi' }
      },
      {
        path: 'wechatpublic',
        name: 'SettingWechatpublic',
        component: () => import('@/views/setting/wechatpublic'),
        meta: { title: '微信公众号管理', icon: 'shezhi' }
      },
      {
        path: 'wechatmini',
        name: 'SettingWechatmini',
        component: () => import('@/views/setting/wechatmini'),
        meta: { title: '小程序管理', icon: 'shezhi' }
      },
      {
        path: 'authorize',
        name: 'SettingAuthorize',
        component: () => import('@/views/setting/authorize'),
        meta: { title: '授权许可', icon: 'shezhi' }
      }
    ]
  },

  {
    path: '/stat',
    component: Layout,
    redirect: '/Stat/order',
    name: 'Stat',
    meta: { title: '统计', icon: 'tongji' },
    children: [
      {
        path: '/Stat/order/order1',
        name: 'StatOrder',
        component: () => import('@/views/stat/order'),
        meta: { title: '订单统计', icon: 'tongji' },
        children: [
          {
            path: 'order1',
            name: 'StatOrder1',
            component: () => import('@/views/stat/order/order1'),
            meta: { title: '订单统计-1', icon: 'tongji' }
          },
          {
            path: 'order2',
            name: 'StatOrder2',
            component: () => import('@/views/stat/order/order2'),
            meta: { title: '订单统计-2', icon: 'tongji' }
          }
        ]
      },
      {
        path: 'index',
        name: 'StatIndex',
        component: () => import('@/views/stat/index'),
        meta: { title: '流水统计', icon: 'tongji' }
      },
      {
        path: 'arpu',
        name: 'StatArpu',
        component: () => import('@/views/stat/arpu'),
        meta: { title: 'ARPU统计', icon: 'tongji' }
      },
      {
        path: 'keep',
        name: 'StatKeep',
        component: () => import('@/views/stat/keep'),
        meta: { title: '留存统计', icon: 'tongji' }
      }
    ]
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
