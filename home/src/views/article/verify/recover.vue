<template>
  <div class="app-container">
    <div class="filter-container">
      <el-select v-model="listQuery.type" style="width: 140px" class="filter-item">
        <el-option v-for="item in type" :key="item.id" :label="item.style" :value="item.id" />
      </el-select>
      <el-input v-model="listQuery.title" placeholder="请输入关键字搜索" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="list" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="180"></el-table-column>
        <el-table-column prop="style" label="布局风格" width="180"></el-table-column>
        <el-table-column prop="title" label="文章标题"></el-table-column>
        <el-table-column label="图片">
          <template slot-scope="scope" >
            <img :src="scope.row.pic" width="200" />
          </template>
        </el-table-column>
        <el-table-column prop="content" label="文章内容"></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" type="success" @click="handleApply(scope.row.id)">申请</el-button>
            <el-button size="mini" type="danger" @click="handleRemove(scope.row.id)">清楚</el-button>
            <el-button size="mini" type="primary">预览</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" style="display: block;" />
  </div>
</template>

<script>
import { statusFilter, statusRemove, status } from '@/api/article'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
export default {
  name: 'ArticleVerifyRecover',
  directives: { waves },
  components: { Pagination },
  inject: ['reload'],
  data() {
    return {
      listLoading: true,
      jsonData: {},
      uploadUri: this.GLOBAL.upload_url,
      dialogStatus: '',
      pid: 1,
      total: 0,
      type: '',
      list: [],
      listQuery: {
        page: 1,
        limit: 10,
        status: 3,
        importance: undefined,
        title: undefined,
        type: undefined
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
        this.type = response.data.type
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
    handleRemove(row) {
      this.jsonData.id = row
      statusRemove(this.jsonData).then(response => {
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
