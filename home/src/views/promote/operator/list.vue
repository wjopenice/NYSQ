<style>
  .filter-container {
    padding-bottom: 10px;
  }
</style>
<template>
  <div class="app-container">
    <div class="filter-container">
      <router-link :to="'edit'">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit">
        新增
      </el-button>
      </router-link>
    </div>
    <div class="filter-container">
    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="运营商名称">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column width="120px" align="center" label="运营商公司名称">
        <template slot-scope="scope">
          <span>{{ scope.row.company_name }}</span>
        </template>
      </el-table-column>

      <el-table-column label="商圈">
        <template slot-scope="scope">
          <el-tag v-for="v in scope.row.tag_id_arr">
              {{busList[v]}}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column label="轮播图">
        <template slot-scope="scope">
          <el-div v-for="v in scope.row.img_id_arr">
            <img :src="imgList[v]" height="50" />
          </el-div>
        </template>
      </el-table-column>

      <el-table-column align="center" label="运营商地址">
        <template slot-scope="scope">
          <span>{{ scope.row.address }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="经纬度">
        <template slot-scope="scope">
          <span>{{ scope.row.longitude}} | {{scope.row.latitude }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="电话">
        <template slot-scope="scope">
          <span>{{ scope.row.phone }}</span>
        </template>
      </el-table-column>

      <el-table-column prop="browse_num" label="浏览数"></el-table-column>

      <el-table-column label="微信二维码">
        <template slot-scope="scope">
          <el-div v-for="v in scope.row.qrcode_id_arr">
            <img :src="imgList[v]" height="50" />
          </el-div>
        </template>
      </el-table-column>

      <el-table-column align="center" label="操作" width="200">
        <template slot-scope="scope">
          <router-link :to="'edit?id='+scope.row.id">
            <el-button type="primary" size="mini" icon="el-icon-edit">编辑</el-button>
          </router-link>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
        <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
    </div>
  </div>
</template>

<script>
import { fetchList,del } from '@/api/operator'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'ArticleList',
  components: { Pagination },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
      }
      return statusMap[status]
    },
    tagName(key) {
      return this.tagList[key]
    }
  },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20
      },
      tagList: [],
      busList: [],
      imgList: []
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.listLoading = false
        this.tagList = response.data.tagList
        this.busList = response.data.busList
        this.imgList = response.data.imgList
      })
    },
    del(id) {
      this.req={};
      this.listLoading = true
      this.req.id = id
      this.$confirm('您确定删除吗？').then(_ => {
        del(this.req).then(response => {
          if (response.data.msg === 'success') {
            this.listLoading = false
            this.$message.success('删除成功')
          } else {
            this.listLoading = false
            this.$message.error(response.data.data)
          }
          this.listLoading = false
        })
      }).catch(_ => {
        this.listLoading = false
      })

    }
  }
}
</script>

<style scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
</style>
