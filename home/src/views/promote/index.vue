<template>
  <div class="app-container">
    <div class="filter-container" style="float: left; width: 30%; height: auto; ">
      <el-form ref="dataForm" :rules="rules" :model="list" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">
        <el-form-item label="所属运营商">
          <el-select v-model="couponSelected" style="width: 290px" class="filter-item" @change="selectData" name="op_id">
            <el-option v-for="item in type" :key="item.id" :label="item.name" :value="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="商圈名称">
          <el-input v-model="temp.b_d_name" name="b_d_name" />
        </el-form-item>
        <el-form-item label="商圈描述">
          <el-input v-model="temp.b_d_info" name="b_d_info" :rows="3" type="textarea" placeholder="请填写描述信息" />
        </el-form-item>
        <el-form-item label="商圈BANNER">
          <el-upload :action="uploadUri" name="b_d_logo" list-type="picture-card" icon="el-icon-upload" :limit = "1" :auto-upload="true" :on-success="handleAvatarSuccess">上传BANNER图片</el-upload>
        </el-form-item>
        <el-form-item label="商圈地址">
          <el-input v-model="temp.b_d_address" name="b_d_address" />
        </el-form-item>
        <el-form-item label="地图坐标经纬度">
          <el-input v-model="temp.b_d_longlat" name="b_d_address" style="border: #00FF00 1px solid;border-radius: 5px;" />
          <span style="color: red; font-weight: bold;">
            1、点击右侧地图<br>
            2、切换对应城市<br>
            3、点击获取指定位置的经纬度<br>
            4、粘贴在此处</span>
        </el-form-item>
        <el-form-item label="包含楼层"><el-input type="textarea"  v-model="temp.b_d_floor" name="b_d_floor" :rows="3" placeholder="多个楼层请用 ，隔开" ></el-input></el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer" style="margin: 30px 0 0 163px;">
        <el-button type="primary" @click="createData">
          保存
        </el-button>
      </div>
    </div>
    <template style="float: right; width: 70%; height: auto; background: red;">
      <div class="act-form">
        <iframe src="http://api.map.baidu.com/lbsapi/getpoint/index.html?region=深圳&location=22.526617,113.921808" width="69%" height="750" frameborder="0" scrolling="no">
          <p>Your browser does not support iframes.</p>
        </iframe>
      </div>
    </template>
  </div>
</template>
<script>
import { fetchType, createArticle } from '@/api/promote'
export default {
  name: 'PromoteIndex',
  inject: ['reload'],
  created() {
    this.getList()
  },
  data() {
    return {
      jsonData: {},
      type: '',
      couponSelected: '',
      uploadUri: this.GLOBAL.upload_url,
      listQuery: {
        page: 1,
        limit: 10,
        importance: undefined,
        title: undefined,
        type: undefined
      },
      temp: {
        op_id: undefined,
        b_d_name: undefined,
        b_d_info: undefined,
        b_d_logo: undefined,
        b_d_address: undefined,
        b_d_floor: undefined,
        b_d_longlat: undefined
      },
      list: {},
      rules: {
        op_id: [{ required: true, message: '缺少运营商', trigger: 'change' }],
        b_d_name: [{ required: true, message: '缺少商圈名称', trigger: 'blur' }],
        b_d_info: [{ required: true, message: '缺少商圈描述', trigger: 'blur' }]
      }
    }
  },
  methods: {
    getList() {
      this.listLoading = true
      fetchType({}).then(response => {
        this.type = response.data.items
        this.couponSelected = this.type[0].id
      })
    },
    selectData(tag) {
      this.temp.op_id = tag
    },
    handleAvatarSuccess(response) {
      this.temp.b_d_logo = response.data
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
