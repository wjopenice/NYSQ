<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        添加商家管理分类ICON
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="list" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="180"></el-table-column>
        <el-table-column prop="title" label="标题" width="180"></el-table-column>
        <el-table-column label="ICON">
          <template slot-scope="scope" >
            <img :src="scope.row.icon" width="50" />
          </template>
        </el-table-column>
        <el-table-column prop="sort" label="排序" width="180"></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">
        <el-form-item label="标题"><el-input v-model="temp.title" /></el-form-item>
        <el-form-item label="ICON">
          <el-upload
            :action="uploadUri"
            list-type="picture-card"
            icon="el-icon-upload"
            :limit = "1"
            :auto-upload="true"
            :on-success="imgsuccess"
          >编辑头像</el-upload>
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
import { getMercList, addMercData } from '@/api/setting'
export default {
  name: 'SettingMercadmin',
  inject: ['reload'],
  data() {
    return {
      list: null,
      rules: {},
      temp: {},
      textMap: {
        update: 'Edit',
        create: 'Create'
      },
      listQuery: {},
      dialogStatus: '',
      dialogFormVisible: false,
      listLoading: true,
      uploadUri: this.GLOBAL.upload_url
    }
  },
  created() {
    this.getlist()
  },
  methods: {
    getlist() {
      this.listLoading = true
      getMercList(this.listQuery).then(response => {
        this.list = response.data.items
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
    handleDelete(index, row) {},
    createData() {
      addMercData(this.temp).then(response => {
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
    updateData() {},
    resetTemp() {
      this.temp = {
        title: undefined,
        icon: undefined
      }
    },
    imgsuccess(response) {
      this.temp.icon = response.data
    }
  }
}
</script>
