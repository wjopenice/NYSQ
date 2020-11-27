<h2>dddd</h2>
<template>
  <div class="app-container" :span="12">
    <el-form ref="form" :model="form" label-width="80px" class="div-form">
      <el-form-item label="运营商名称">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="运营商公司名称">
        <el-input v-model="form.company_name"></el-input>
      </el-form-item>
      <el-form-item label="地址">
        <el-input v-model="form.address"></el-input>
      </el-form-item>
      <el-form-item label="手机">
        <el-input v-model="form.phone"></el-input>
      </el-form-item>
      <el-form-item label="经纬度">
        <el-input v-model="form.longlatitude"></el-input>
      </el-form-item>
      <el-form-item label="视频链接地址">
        <el-input v-model="form.video_url"></el-input>
      </el-form-item>
      <el-form-item label="上传轮播图">
        <el-upload :action="local_upload_url" :on-success="uploadBanner" :on-remove="onRemoveBanner" :file-list="bannerList" list-type="picture-card" icon="el-icon-upload" :limit = "9" :auto-upload="true">上传图片(最多9张)</el-upload>
      </el-form-item>
      <el-form-item label="上传二维码图">
        <el-upload :action="local_upload_url" :on-success="uploadQrcode" :on-remove="onRemoveQrcode" :file-list="qrcodeList" list-type="picture-card" icon="el-icon-upload" :limit = "9" :auto-upload="true">上传图片(最多9张)</el-upload>
      </el-form-item>
      <el-form-item label="选择标签">
        <el-checkbox-group v-model="form.tag_ids">
          <el-checkbox v-for="v in tagList" :label="v.id" :key="v.id">{{v.b_d_name}}</el-checkbox>
        </el-checkbox-group>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="onSubmit"><label v-if="form.id == 0">立即创建</label><label v-else>修改</label></el-button>
        <router-link :to="'list'"><el-button>返回</el-button></router-link>
      </el-form-item>
    </el-form>

</div>
</template>

<script>
import { add,getInfo,getBusinessDistr } from '@/api/operator'
import Dropzone from '@/components/Dropzone'

export default {
  name: 'ArticleList',
  components: { Dropzone },
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
      uploadUri: this.GLOBAL.upload_url,
      local_upload_url: this.GLOBAL.local_upload_url,
      tagList:[],
      bannerList:[],
      qrcodeList:[],
      form: {
        name: '',
        company_name: '',
        address: '',
        phone: '',
        longlatitude: '',
        latitude: '',
        video_url: '',
        banner_ids: [],
        qrcode_img_ids: [],
        tag_ids:[],
        id: 0
      }
    }
  },
  created() {
    if( "undefined" !== typeof (this.$route.query.id)) this.form.id = this.$route.query.id
    this.getTagInfo()
    if(this.form.id > 0) this.getInfo()
  },
  methods: {
    onSubmit() {
      console.log('submit!')
      this.listLoading = true
      add(this.form).then(response => {
        console.log(response)
        if (response.msg === 'success') {
          this.$message.success(response.data)
        } else {
          this.$message.error(response.data)
        }
        this.listLoading = false
      })
    },
    uploadBanner(file){
      this.form.banner_ids.push(parseInt(file.data.id))
    },
    uploadQrcode(file){
      console.log(file)
      this.form.qrcode_img_ids.push(file.data.id)
    },
    onRemoveBanner(file){
      console.log(file)
      var id = parseInt(file.id)
      this.form.banner_ids.splice(this.form.banner_ids.indexOf(id),1)
    },
    onRemoveQrcode(file){
      console.log(file)
      var id = parseInt(file.id)
      this.form.qrcode_img_ids.splice(this.form.qrcode_img_ids.indexOf(id)+1,1)
    },
    getTagInfo(){
      this.req = {}
      this.listLoading = true
      getBusinessDistr(this.req).then(response => {
        this.tagList = response.data
        this.listLoading = false
      })
    },
    getInfo(){
      this.req = {}
      this.listLoading = true
      this.req.id = this.$route.query.id
      getInfo(this.req).then(response => {
        console.log(response.data)
        this.form.name = response.data.name
        this.form.company_name = response.data.company_name
        this.form.address = response.data.address
        this.form.longlatitude = response.data.longitude + ',' + response.data.latitude
        this.form.phone = response.data.phone
        this.form.banner_ids = response.data.banner_ids
        this.form.qrcode_img_ids = response.data.qrcode_img_ids
        this.form.video_url = response.data.video_url
        this.form.tag_ids = response.data.tag_ids
        this.bannerList = response.data.bannerList
        this.qrcodeList = response.data.qrcodeList
        this.listLoading = false
      })
    }
  }
}
</script>
