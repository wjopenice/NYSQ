<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.search" placeholder="请输入关键字搜索" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">搜索</el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">添加系统标签</el-button>
    </div>
    <div class="filter-container">
      <el-table :data="list" stripe style="width: 800px;">
        <el-table-column prop="id" label="ID" width="180"></el-table-column>
        <el-table-column min-width="180" label="标签内容">
          <template slot-scope="{row}">
            <template v-if="row.edit">
              <el-input v-model="row.name" class="edit-input" size="small" style="float: left;width: 200px;" />
              <el-button class="cancel-btn" size="small" icon="el-icon-refresh" type="warning" @click="cancelEdit(row)" style="float: left;"> 取消</el-button>
            </template>
            <span v-else>{{ row.name }}</span>
          </template>
        </el-table-column>

        <el-table-column align="center" label="操作" width="300">
            <template slot-scope="{row}">
              <el-button v-if="row.edit" type="success" size="small" icon="el-icon-circle-check-outline" @click="confirmEdit(row)"> 确定 </el-button>
              <el-button v-else type="primary" size="small" icon="el-icon-edit" @click="row.edit=!row.edit"> 修改 </el-button>
              <el-button size="small" type="danger" icon="el-icon-remove" @click="handleRemove(row.id)">删除</el-button>
            </template>
        </el-table-column>

      </el-table>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules"  :model="temp" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">
        <el-form-item label="系统标签">
          <el-input name="name" v-model="namedata" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer" style="margin: 30px 0 0 163px;">
        <el-button type="primary" @click="createData">
          保存
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { statusRemove, getTagList, saveList, createTag } from '@/api/tag'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
export default {
  name: 'PromoteTag',
  directives: { waves },
  components: { Pagination },
  inject: ['reload'],
  data() {
    return {
      listLoading: true,
      dialogFormVisible: false,
      jsonData: {},
      req: {},
      uploadUri: this.GLOBAL.upload_url,
      dialogStatus: '',
      pid: 1,
      total: 0,
      type: '',
      list: [],
      namedata: '',
      temp: {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        type: '',
        status: 'published'
      },
      listQuery: {
        page: 1,
        limit: 10,
        status: 1,
        importance: undefined,
        search: undefined,
        type: undefined
      },
      rules: {
        type: [{ required: true, message: 'type is required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      },
      textMap: {
        create: '添加'
      }
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      getTagList(this.listQuery).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.type = response.data.type
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        status: 'published',
        type: ''
      }
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleRemove(row) {
      this.jsonData.id = row
      this.jsonData.switchtype = 'error'
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
    },
    cancelEdit(row) {
      row.title = row.originalTitle
      row.edit = false
    },
    confirmEdit(row) {
      row.edit = false
      row.originalTitle = row.title
      this.jsonData = {}
      this.jsonData.id = row.id
      this.jsonData.name = row.name
      saveList(this.jsonData).then(response => {
        if (response.data === 'success') {
          this.$message.success('保存成功')
        } else {
          this.$message.error('操作失败')
        }
      })
    },
    createData() {
      console.log(this.namedata)
      this.req = {}
      this.req.type = 1
      this.req.name = this.namedata
      this.req.operator_id = 1
      createTag(this.req).then(response => {
        if (response.data === 'success') {
          this.$message.success('添加成功')
          setTimeout(() => {
            this.reload()
          }, 1.5 * 1000)
        } else {
          this.$message.error('添加失败')
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

