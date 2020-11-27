<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.search" placeholder="请输入商品名称" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        添加商品
      </el-button>
    </div>
    <div class="filter-container">
      <el-table :data="list" stripe style="width: 100%">
        <el-table-column prop="id" label="ID" width="50"></el-table-column>
        <el-table-column prop="merc_name" label="商家" width="180"></el-table-column>
        <el-table-column prop="p_name" label="商品名称" width="180"></el-table-column>
        <el-table-column prop="p_description" label="商品描述" width="200"></el-table-column>
        <el-table-column label="商品BANNER" width="180">
          <template slot-scope="scope" ><img :src="scope.row.p_banner" width="100" /></template>
        </el-table-column>
        <el-table-column label="上新" width="100">
          <template slot-scope="scope"><el-switch v-model="scope.row.p_news" active-color="#00FF00" inactive-color="#999999" @change="handleNews(scope.row)"></el-switch></template>
        </el-table-column>
        <el-table-column label="热销" width="100">
          <template slot-scope="scope"><el-switch v-model="scope.row.p_hot" active-color="#00FF00" inactive-color="#999999" @change="handleHot(scope.row)"></el-switch></template>
        </el-table-column>
        <el-table-column label="推荐" width="100">
          <template slot-scope="scope"><el-switch v-model="scope.row.p_top" active-color="#00FF00" inactive-color="#999999" @change="handleTop(scope.row)"></el-switch></template>
        </el-table-column>
        <el-table-column label="排序" width="100">
          <template slot-scope="scope"><el-input v-model="scope.row.p_sort" @focus="handleSort(scope.row)" /></template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="medium" type="primary" @click="handleEdit(scope.row.id)">编辑</el-button>
            <el-button size="medium" type="success">上架</el-button>
            <el-button size="medium" type="warning">下架</el-button>
            <el-button size="medium" type="danger" @click="handleDelete(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList"  />
    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="110px" style="width: 750px; margin-left:50px;">
        <el-form-item label="选择商家">
          <el-select v-model="couponSelected" style="width: 640px" class="filter-item" @change="selectData" name="op_id">
            <el-option v-for="item in merc" :key="item.m_id" :label="item.merc_name" :value="item.m_id" />
          </el-select>
        </el-form-item>
        <el-form-item label="商品名称">
          <el-input v-model="temp.p_name" />
        </el-form-item>
        <el-form-item label="商品描述">
          <el-input v-model="temp.p_description" name="b_d_info" :rows="3" type="textarea" placeholder="请填写描述信息" />
        </el-form-item>
        <el-form-item label="商品BANNER">
          <el-upload :action="uploadUri" :file-list="temp.p_banner" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="imgsuccess1">上传BANNER图片</el-upload>
        </el-form-item>
        <el-form-item label="商品细节图1">
          <el-upload :action="uploadUri" :file-list="temp.p_pic1" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="imgsuccess2">上传细节图1</el-upload>
        </el-form-item>
        <el-form-item label="商品细节图2">
          <el-upload :action="uploadUri"  :file-list="temp.p_pic2" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="imgsuccess3">上传细节图2</el-upload>
        </el-form-item>
        <el-form-item label="商品细节图3">
          <el-upload :action="uploadUri" :file-list="temp.p_pic3" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="imgsuccess4">上传细节图3</el-upload>
        </el-form-item>
        <el-form-item label="商品细节图4">
          <el-upload :action="uploadUri"  :file-list="temp.p_pic4" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="imgsuccess5">上传细节图4</el-upload>
        </el-form-item>
        <el-form-item label="商品细节图5">
          <el-upload :action="uploadUri"  :file-list="temp.p_pic5" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="imgsuccess6">上传细节图5</el-upload>
        </el-form-item>
        <el-form-item label="上新">
          <el-switch v-model="temp.p_news" active-value="1" inactive-value="0" active-color="#00FF00" inactive-color="#999999" @change="handleStatus(temp.p_news,'news')"></el-switch>
        </el-form-item>
        <el-form-item label="热销">
          <el-switch v-model="temp.p_hot" active-value="1" inactive-value="0" active-color="#00FF00" inactive-color="#999999" @change="handleStatus(temp.p_hot,'hot')"></el-switch>
        </el-form-item>
        <el-form-item label="推荐">
          <el-switch v-model="temp.p_top" active-value="1" inactive-value="0" active-color="#00FF00" inactive-color="#999999" @change="handleStatus(temp.p_top,'top')"></el-switch>
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
import {
  getShopList,
  statusShopSwitch,
  statusShopSort,
  removeShop,
  createArticle,
  selectArticle,
  updateArticle
} from '@/api/shop'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination'
export default {
  name: 'ShopIndex',
  directives: { waves },
  components: { Pagination },
  inject: ['reload'],
  data() {
    return {
      editid: undefined,
      listLoading: true,
      total: 0,
      dialogFormVisible: false,
      dialogStatus: '',
      list: [],
      merc: '',
      reqdata: {},
      uploadUri: this.GLOBAL.upload_url,
      textMap: {
        update: '修改',
        create: '添加'
      },
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        search: undefined,
        sort: '+id'
      },
      temp: {
        m_id: undefined,
        p_name: undefined,
        p_description: '',
        p_banner: '',
        p_pic1: '',
        p_pic2: '',
        p_pic3: '',
        p_pic4: '',
        p_pic5: '',
        p_news: 0,
        p_hot: 0,
        p_top: 0,
        p_sort: 0
      },
      rules: {},
      couponSelected: ''
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      getShopList(this.listQuery).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.merc = response.data.merc
        this.couponSelected = this.merc[0].id
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    selectData(tag) {
      this.temp.m_id = tag
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    resetTemp() {
      this.temp = {
        m_id: undefined,
        p_name: undefined,
        p_description: undefined,
        p_banner: undefined,
        p_pic1: undefined,
        p_pic2: undefined,
        p_pic3: undefined,
        p_pic4: undefined,
        p_pic5: undefined,
        p_news: 0,
        p_hot: 0,
        p_top: 0,
        p_sort: 0
      }
    },
    handleDelete(row) {
      this.reqdata = {}
      this.reqdata.id = row
      removeShop(this.reqdata).then(response => {
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
    handleNews(row) {
      this.reqdata = {}
      this.reqdata.id = row.id
      this.reqdata.status = row.p_news
      this.reqdata.type = 'news'
      statusShopSwitch(this.reqdata).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
        } else {
          this.$message.error('操作失败')
        }
      })
    },
    handleHot(row) {
      this.reqdata = {}
      this.reqdata.id = row.id
      this.reqdata.status = row.p_hot
      this.reqdata.type = 'hot'
      statusShopSwitch(this.reqdata).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
        } else {
          this.$message.error('操作失败')
        }
      })
    },
    handleTop(row) {
      this.reqdata = {}
      this.reqdata.id = row.id
      this.reqdata.status = row.p_top
      this.reqdata.type = 'top'
      statusShopSwitch(this.reqdata).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
        } else {
          this.$message.error('操作失败')
        }
      })
    },
    handleSort(row) {
      this.reqdata = {}
      this.reqdata.id = row.id
      this.reqdata.status = row.p_sort
      statusShopSort(this.reqdata).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
        } else {
          this.$message.error('操作失败')
        }
      })
    },
    handleEdit(index) {
      this.reqdata = {}
      this.reqdata.shop_id = index
      this.editid = index
      selectArticle(this.reqdata).then(response => {
        this.couponSelected = response.data.items.m_id
        this.temp = response.data.items
        this.temp.p_banner = response.data.items.p_banner === '' ? undefined : response.data.items.p_banner
        this.temp.p_pic1 = response.data.items.p_pic1 === '' ? undefined : response.data.items.p_pic1
        this.temp.p_pic2 = response.data.items.p_pic2 === '' ? undefined : response.data.items.p_pic2
        this.temp.p_pic3 = response.data.items.p_pic3 === '' ? undefined : response.data.items.p_pic3
        this.temp.p_pic4 = response.data.items.p_pic4 === '' ? undefined : response.data.items.p_pic4
        this.temp.p_pic5 = response.data.items.p_pic5 === '' ? undefined : response.data.items.p_pic5
        this.dialogStatus = 'update'
        this.dialogFormVisible = true
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
        })
      })
    },
    handleStatus(row, type) {
      switch (type) {
        case 'news': this.temp.p_news = row
          break
        case 'hot': this.temp.p_hot = row
          break
        case 'top': this.temp.p_top = row
          break
      }
    },
    imgsuccess1(response) {
      this.temp.p_banner = response.data
    },
    imgsuccess2(response) {
      this.temp.p_pic1 = response.data
    },
    imgsuccess3(response) {
      this.temp.p_pic2 = response.data
    },
    imgsuccess4(response) {
      this.temp.p_pic3 = response.data
    },
    imgsuccess5(response) {
      this.temp.p_pic4 = response.data
    },
    imgsuccess6(response) {
      this.temp.p_pic5 = response.data
    },
    createData() {
      createArticle(this.temp).then(response => {
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
    updateData() {
      this.temp.id = this.editid
      updateArticle(this.temp).then(response => {
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
/deep/.el-upload-list--picture-card {
  margin: 0;
  display: inline;
  vertical-align: top;
  float: left;
  width: 300px;
}
/deep/.el-upload--picture-card {
  background-color: #fbfdff;
  border: 1px dashed #c0ccda;
  border-radius: 6px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: 339px;
  height: 140px;
  line-height: 146px;
  vertical-align: top;
}
/deep/.el-upload-list--picture-card .el-upload-list__item {
  overflow: hidden;
  background-color: #fff;
  border: 1px solid #c0ccda;
  border-radius: 6px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: 300px;
  height: 138px;
  margin: 0 8px 8px 0;
  display: inline-block;
}
</style>
