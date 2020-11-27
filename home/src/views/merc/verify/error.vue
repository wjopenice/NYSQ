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
        <el-table-column prop="m_id" label="ID" width="150"></el-table-column>
        <el-table-column prop="b_d_name" label="所属商圈" width="180"></el-table-column>
        <el-table-column prop="merc_name" label="商铺名称" width="180"></el-table-column>
        <el-table-column label="商铺LOGO" width="150">
          <template slot-scope="scope" >
            <img :src="scope.row.merc_logo" width="100" />
          </template>
        </el-table-column>

        <el-table-column label="系统标签">
          <template slot-scope="scope">
            <el-tag v-for="v in scope.row.merc_sys_tag">
              {{tagList[v]}}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="风格标签">
          <template slot-scope="scope">
            <el-tag v-for="v in scope.row.merc_diy_tag">
              {{tagList[v]}}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="楼层" width="80" >
          <template slot-scope="scope">
            <el-tag type="success" effect="dark">{{ scope.row.merc_floor }}F</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="入驻时间" width="150" align="center">
          <template slot-scope="{row}">
            <span>{{ row.create_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" type="success" @click="handleApply(scope.row.m_id)">申请</el-button>
            <el-button size="mini" type="danger" @click="handleRecover(scope.row.m_id)">删除</el-button>
            <el-button size="mini" type="primary">预览</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" style="display: block;" />
  </div>
</template>

<script>
import { statusFilter, status } from '@/api/merc'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
import moment from 'moment'
export default {
  name: 'MercVerifyError',
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
        status: 2,
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
    handleApply(row) {
      this.jsonData.id = row
      this.jsonData.switchtype = 'apply'
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
    },
    handleRecover(row) {
      this.jsonData.id = row
      this.jsonData.switchtype = 'recover'
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
  .dashboard {
    &-container {
      margin: 30px;
    }
    &-text {
      font-size: 30px;
      line-height: 46px;
    }
  }
  .el-upload--picture-card{
    height: 70px !important;
    line-height: 70px !important;
  }
  /deep/.el-form-item__content {
    line-height: 40px;
    position: relative;
    font-size: 14px;
    display: flex;
    .el-upload--picture-card {
      background-color: #fbfdff;
      border: 1px dashed #c0ccda;
      border-radius: 6px;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      width: 148px;
      height: 72px;
      line-height: 69px;
      vertical-align: top;
    }
  }
</style>
