<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        添加角色
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="listData" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="50"></el-table-column>
        <el-table-column prop="roles" label="角色" width="300"></el-table-column>
        <el-table-column align="center" label="Operations">
          <template slot-scope="scope">
            <el-button type="primary" size="medium" @click="handleEdit(scope)">编辑</el-button>
            <el-button type="danger" size="medium">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <el-dialog :visible.sync="dialogFormVisible" :title="dialogType==='edit'?'Edit Role':'New Role'">
      <el-form :model="temp" label-width="80px" label-position="left">
        <el-form-item label="角色">
          <el-input v-model="temp.roles" placeholder="Role Name" />
        </el-form-item>
        <el-form-item label="权限">
          <el-tree
            ref="tree"
            :check-strictly="checkStrictly"
            :data="routesData"
            :props="defaultProps"
            show-checkbox
            node-key="path"
            class="permission-tree"
          />
        </el-form-item>
      </el-form>
      <div style="text-align:right;">
        <el-button type="danger" @click="dialogFormVisible=false">取消</el-button>
        <el-button type="primary">提交</el-button>
      </div>
    </el-dialog>

<!--    <el-dialog title="Create" :visible.sync="dialogFormVisible">-->
<!--      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">-->
<!--        <el-form-item label="角色"><el-input v-model="temp.roles" /></el-form-item>-->
<!--      </el-form>-->
<!--      <div slot="footer" class="dialog-footer" style="margin: 30px 0 0 163px;">-->
<!--        <el-button type="primary" @click="createData">-->
<!--          保存-->
<!--        </el-button>-->
<!--      </div>-->
<!--    </el-dialog>-->
  </div>
</template>
<script>
import { getRoles, addRoles } from '@/api/platform'
import { deepClone } from '@/utils'
const defaultRole = {
  roles: '',
  routes: []
}
export default {
  name: 'UserManagementRoles',
  inject: ['reload'],
  data() {
    return {
      dialogType: 'new',
      listLoading: true,
      dialogFormVisible: false,
      checkStrictly: false,
      listQuery: {},
      temp: {},
      listData: [],
      rules: {},
      role: Object.assign({}, defaultRole),
      routes: [],
      defaultProps: {
        children: 'children',
        label: 'title'
      }
    }
  },
  computed: {
    routesData() {
      return this.routes
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      getRoles(this.listQuery).then(response => {
        this.listData = response.data.items
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleCreate() {
      this.resetTemp()
      this.dialogFormVisible = true
    },
    createData() {
      this.listQuery.roles = this.temp.roles
      addRoles(this.listQuery).then(response => {
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
        roles: undefined
      }
    },
    handleEdit(scope) {
      this.dialogType = 'edit'
      this.dialogFormVisible = true
      this.checkStrictly = true
      this.role = deepClone(scope.row)
      this.$nextTick(() => {
        const routes = this.generateRoutes(this.role.routes)
        this.$refs.tree.setCheckedNodes(this.generateArr(routes))
        // set checked state of a node not affects its father and child nodes
        this.checkStrictly = false
      })
    }
  }
}
</script>
<style lang="scss" scoped>

</style>

