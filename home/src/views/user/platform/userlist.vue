<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.search" placeholder="请输入微信昵称" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="listData" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="50"></el-table-column>
        <el-table-column prop="openid" label="OPENID" width="300"></el-table-column>
        <el-table-column prop="nickname" label="微信昵称" width="100"></el-table-column>
        <el-table-column label="微信头像" width="100">
          <template slot-scope="scope" ><img :src="scope.row.headimgurl" width="50" /></template>
        </el-table-column>
        <el-table-column label="性别" width="100">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.sex === '1' ">男</el-tag>
            <el-tag v-else-if="scope.row.sex === '2' ">女</el-tag>
            <el-tag v-else>未知</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="country" label="国家" width="100"></el-table-column>
        <el-table-column prop="province" label="省" width="100"></el-table-column>
        <el-table-column prop="city" label="市" width="100"></el-table-column>
        <el-table-column label="关注日期" width="150" align="center">
          <template slot-scope="{row}">
            <span>{{ row.subscribe_time | formatDate }}</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>
<script>
import { list } from '@/api/platform'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
import moment from 'moment/moment'
export default {
  name: 'UserPlatformUserlist',
  components: { Pagination },
  directives: { waves },
  filters: {
    formatDate: function(tag) {
      return moment(tag * 1000).format('YYYY-MM-DD')
    }
  },
  data() {
    return {
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        limit: 8,
        search: undefined
      },
      temp: {},
      listData: []
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      list(this.listQuery).then(response => {
        this.listData = response.data.items
        this.total = response.data.total
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
