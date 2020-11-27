<template>
  <div class="dashboard-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        添加专题分类
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="list" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="180"></el-table-column>
        <el-table-column prop="title" label="标题" width="180"></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" type="danger" @click="handleDelete(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog title="Create" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">
        <el-form-item label="标题"><el-input v-model="temp.title" /></el-form-item>
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
import { thematic, thematicAdd, thematicDel } from '@/api/article'
export default {
  name: 'ArticleImport',
  inject: ['reload'],
  directives: { },
  components: { },
  data() {
    return {
      list: null,
      rules: {},
      temp: {},
      listQuery: {},
      dialogFormVisible: false,
      listLoading: true,
      uploadUri: this.GLOBAL.upload_url
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      thematic(this.listQuery).then(response => {
        this.list = response.data.items
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleCreate() {
      this.resetTemp()
      this.dialogFormVisible = true
    },
    handleDelete(id) {
      this.listQuery.id = id
      thematicDel(this.listQuery).then(response => {
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
    createData() {
      this.listQuery.title = this.temp.title
      thematicAdd(this.listQuery).then(response => {
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
    resetTemp() {
      this.temp = {
        title: undefined
      }
    },
    imgsuccess(response) {
      this.temp.icon = response.data
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
/deep/.el-upload-list--picture-card {
  margin: 0;
  display: inline;
  vertical-align: top;
  float: left;
  width: 150px;
}
/deep/.el-upload--picture-card {
  background-color: #fbfdff;
  border: 1px dashed #c0ccda;
  border-radius: 6px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: 140px;
  height: 140px;
  line-height: 146px;
  vertical-align: top;
}
</style>
