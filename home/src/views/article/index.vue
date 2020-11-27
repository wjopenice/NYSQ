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
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        添加文章
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
            <el-button size="mini" @click="handleEdit(scope.$index, scope.row.id)">编辑</el-button>
            <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" style="display: block;" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">
        <el-form-item label="文章风格">
          <el-select v-model="listQuery.type" style="width: 140px" class="filter-item" @change="selectData">
            <el-option v-for="item in type" :key="item.id" :label="item.style" :value="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="文章标题">
          <el-input v-model="temp.title" />
        </el-form-item>
        <el-form-item label="文章摘要">
          <el-input v-model="temp.describe" />
        </el-form-item>
        <el-form-item label="文章展示图" v-if="pid == 6">
          <el-upload :action="uploadUri" list-type="picture-card" icon="el-icon-upload" :limit = "9" :auto-upload="true">上传图片(最多9张)</el-upload>
        </el-form-item>
        <el-form-item label="文章展示图" v-else-if="pid == 8">
          <el-upload :action="uploadUri" list-type="picture-card" icon="el-icon-upload" :limit = "3" :auto-upload="true">上传图片(最多3张)</el-upload>
        </el-form-item>
        <el-form-item label="文章展示图" v-else>
          <el-upload :action="uploadUri" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true">上传图片</el-upload>
        </el-form-item>
        <el-form-item label="文章内容">
          <Tinymce ref="editor" v-model="temp.title" :height="400" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer" style="margin: 30px 0 0 163px;">
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          保存
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { list, createArticle, updateArticle } from '@/api/article'
import Tinymce from '@/components/Tinymce'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JP', display_name: 'Japan' },
  { key: 'EU', display_name: 'Eurozone' }
]
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})
export default {
  name: 'ArticleIndex',
  directives: { waves },
  components: { Pagination, Tinymce },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
      }
      return statusMap[status]
    },
    typeFilter(type) {
      return calendarTypeKeyValue[type]
    }
  },
  data() {
    return {
      listLoading: true,
      dialogFormVisible: false,
      imagecropperShow: false,
      uploadUri: this.GLOBAL.upload_url,
      dialogStatus: '',
      pid: 1,
      total: 0,
      type: '',
      list: [],
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
        importance: undefined,
        title: undefined,
        type: undefined
      },
      rules: {
        type: [{ required: true, message: 'type is required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      },
      textMap: {
        update: 'Edit',
        create: 'Create'
      },
      activeName: '1'
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      list(this.listQuery).then(response => {
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
    selectData(tag) {
      this.pid = tag
    },
    handleEdit(index, row) {
      console.log(index, row)
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    handleDelete(index, row) {
      console.log(index, row)
    },
    close() {
      this.imagecropperShow = false
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
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.temp.id = parseInt(Math.random() * 100) + 1024 // mock a id
          this.temp.author = 'vue-element-admin'
          createArticle(this.temp).then(() => {
            this.list.unshift(this.temp)
            this.$notify({
              title: 'Success',
              message: 'Created Successfully',
              type: 'success',
              duration: 2000
            })
          })
        }
      })
    },
    handleRemove(file) {
      console.log(file)
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          tempData.timestamp = +new Date(tempData.timestamp) // change Thu Nov 30 2017 16:41:05 GMT+0800 (CST) to 1512031311464
          updateArticle(tempData).then(() => {
            for (const v of this.list) {
              if (v.id === this.temp.id) {
                const index = this.list.indexOf(v)
                this.list.splice(index, 1, this.temp)
                break
              }
            }
            this.dialogFormVisible = false
            this.$notify({
              title: 'Success',
              message: 'Update Successfully',
              type: 'success',
              duration: 2000
            })
          })
        }
      })
    },
    handleClick(tab, event) {
      console.log(tab, event)
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
