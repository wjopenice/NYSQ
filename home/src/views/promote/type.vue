<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.search" placeholder="请输入关键字搜索" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="list" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="50"></el-table-column>
        <el-table-column prop="op_id" label="所属运营商" width="180"></el-table-column>
        <el-table-column prop="b_d_name" label="商圈名称" width="180"></el-table-column>
        <el-table-column prop="b_d_info" label="商圈描述" width="180"></el-table-column>
        <el-table-column label="商圈BANNER" width="150">
          <template slot-scope="scope" >
            <img :src="scope.row.b_d_logo" width="100" />
          </template>
        </el-table-column>
        <el-table-column prop="b_d_address" label="商圈地址" width="180"></el-table-column>
        <el-table-column label="楼层" width="300" >
          <template slot-scope="scope">
            <el-tag type="success" effect="dark" v-for="index in scope.row.b_d_floor">{{ index }}F</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="入驻时间" width="150" align="center">
          <template slot-scope="{row}">
            <span>{{ row.b_d_creata_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" type="danger" @click="handleError(scope.row.m_id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" style="display: block;" />
  </div>
</template>
<script>
import { statusFilter, status } from '@/api/promote'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
import moment from 'moment'
export default {
  name: 'PromoteType',
  directives: { waves },
  components: { Pagination },
  inject: ['reload'],
  filters: {
    formatDate: function(tag) {
      return moment(tag * 1000).format('YYYY-MM-DD')
    }
  },
  data() {
    return {
      listLoading: true,
      jsonData: {},
      uploadUri: this.GLOBAL.upload_url,
      dialogStatus: '',
      pid: 1,
      total: 0,
      list: [],
      tagList: [],
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        search: undefined
      }
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      status(this.listQuery).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.tagList = response.data.tagList
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleError(row) {
      this.jsonData = {}
      this.jsonData.id = row
      statusFilter(this.jsonData).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
          setTimeout(() => {
            this.reload()
          }, 1.5 * 1000)
        } else {
          this.$message.error('操作失败')
        }
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
