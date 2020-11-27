<template>
  <div class="app-container">
    <div class="filter-container">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="110px" style="width: 800px; margin-left:50px;">
        <el-form-item label="所属商圈">
          <el-select v-model="couponSelected" style="width: 140px" class="filter-item" @change="selectData">
            <el-option v-for="item in type" :key="item.id" :label="item.b_d_name" :value="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="商家名称"><el-input v-model="temp.merc_name" @blur="saveaddress" /></el-form-item>
        <el-form-item label="楼层">
          <el-radio v-model="temp.merc_floor" :label="index" v-for="index in jsonData" @change="saveaddress">{{ index }}楼</el-radio>
        </el-form-item>
        <el-form-item label="房间号/档口号"><el-input v-model="temp.room_number" maxlength="10" @blur="saveaddress" /></el-form-item>
        <el-form-item label="商家地址"><el-input v-model="temp.merc_address" readonly /></el-form-item>
        <el-form-item label="商家LOGO">
          <el-upload :action="uploadUri" :file-list="temp.merc_logo" list-type="picture-card" icon="el-icon-upload" :limit= "1" :auto-upload="true" :on-success="imgsuccess1">上传LOGO</el-upload>
        </el-form-item>
        <el-form-item label="商家简介"><el-input v-model="temp.merc_info" /></el-form-item>
        <el-form-item label="商家描述"><el-input v-model="temp.merc_description" type="textarea" :rows="3" placeholder="请输入内容"></el-input></el-form-item>
        <el-form-item label="系统标签">
          <el-checkbox-group v-model="temp.merc_sys_tag">
            <el-checkbox :label="vis.id" v-for="vis in sysTag">{{ vis.name }}</el-checkbox><br>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="风格标签">
          <el-checkbox-group v-model="temp.merc_diy_tag">
            <el-checkbox :label="vis.id" v-for="vis in diyTag">{{ vis.name }}</el-checkbox><br>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="商家轮播图">
          <el-upload :action="uploadUri" :file-list="temp.merc_banner" list-type="picture-card" icon="el-icon-upload" :limit= "5" :auto-upload="true" :on-success="imgsuccess2">上传图片(最多5张)</el-upload>
        </el-form-item>
        <el-form-item label="手机号"><el-input v-model="temp.merc_tel" type="textarea" :rows="3" placeholder="多个手机号请用 ，隔开" ></el-input></el-form-item>
        <el-form-item label="微信二维码">
          <el-upload :action="uploadUri" :file-list="temp.merc_wechat_qrcode" list-type="picture-card" icon="el-icon-upload" :limit= "4" :auto-upload="true" :on-success="imgsuccess3">上传图片</el-upload>
        </el-form-item>
        <el-form-item label="公众号地址"><el-input v-model="temp.merc_wechat_pub_address" /></el-form-item>
        <el-form-item label="公众号二维码">
          <el-upload :action="uploadUri" :file-list="temp.merc_wechat_pub_qrcode" list-type="picture-card" icon="el-icon-upload" :limit= "1" :auto-upload="true" :on-success="imgsuccess4">上传图片</el-upload>
        </el-form-item>
        <el-form-item label="微商相册地址"><el-input v-model="temp.merc_weshop_photo_address" /></el-form-item>
        <el-form-item label="微商相册二维码">
          <el-upload :action="uploadUri" :file-list="temp.merc_weshop_photo_qrcode" list-type="picture-card" icon="el-icon-upload" :limit= "1" :auto-upload="true" :on-success="imgsuccess5">上传图片</el-upload>
        </el-form-item>
        <el-form-item label="短视频地址"><el-input v-model="temp.merc_video_address" /></el-form-item>
        <el-form-item label="短视频二维码">
          <el-upload :action="uploadUri" :file-list="temp.merc_video_qrcode" list-type="picture-card" icon="el-icon-upload" :limit= "1" :auto-upload="true" :on-success="imgsuccess6">上传图片</el-upload>
        </el-form-item>
        <el-form-item label="直播地址"><el-input v-model="temp.merc_live_address" /></el-form-item>
        <el-form-item label="直播二维码">
          <el-upload :action="uploadUri" :file-list="temp.merc_live_qrcode" list-type="picture-card" icon="el-icon-upload" :limit= "1" :auto-upload="true" :on-success="imgsuccess7">上传图片</el-upload>
        </el-form-item>
        <el-form-item label="有赞微商城地址"><el-input v-model="temp.merc_zan_address" /></el-form-item>
        <el-form-item label="有赞微商城二维码">
          <el-upload :action="uploadUri" :file-list="temp.merc_zan_qrcode" list-type="picture-card" icon="el-icon-upload" :limit= "1" :auto-upload="true" :on-success="imgsuccess8">上传图片</el-upload>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer" style="margin: 30px 0 0 163px;">
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          保存
        </el-button>
      </div>
    </div>
  </div>
</template>
<script>
import { list, selectfloor, insertList, findList, updateList } from '@/api/merc'
import waves from '@/directive/waves'
export default {
  name: 'MercIndexSave',
  directives: { waves },
  inject: ['reload'],
  data() {
    return {
      listLoading: true,
      couponSelected: '',
      selectName: '',
      dialogStatus: '',
      uploadUri: this.GLOBAL.upload_url,
      sysTag: [],
      diyTag: [],
      jsonData: {},
      type: '',
      temp: {
        b_d_id: undefined,
        merc_name: undefined,
        merc_logo: undefined,
        merc_info: undefined,
        merc_description: undefined,
        merc_sys_tag: [],
        merc_diy_tag: [],
        room_number: undefined,
        merc_floor: undefined,
        merc_banner: [],
        merc_address: undefined,
        merc_tel: undefined,
        merc_wechat_qrcode: [],
        merc_wechat_pub_address: undefined,
        merc_wechat_pub_qrcode: undefined,
        merc_weshop_photo_address: undefined,
        merc_weshop_photo_qrcode: undefined,
        merc_video_address: undefined,
        merc_video_qrcode: undefined,
        merc_live_address: undefined,
        merc_live_qrcode: undefined,
        merc_zan_address: undefined,
        merc_zan_qrcode: undefined
      },
      rules: {},
      listQuery: {}
    }
  },
  created() {
    this.getList()
    this.dialogStatus = (this.$route.query.id === undefined) ? 'create' : 'update'
  },
  methods: {
    getList() {
      this.listLoading = true
      if (this.$route.query.id === undefined) {
        list(this.listQuery).then(response => {
          this.type = response.data.bdList
          this.sysTag = response.data.sysTag
          this.diyTag = response.data.diyTag
        })
      } else {
        this.listQuery.editid = this.$route.query.id
        console.log(this.listQuery)
        findList(this.listQuery).then(response => {
          this.type = response.data.bdList
          this.sysTag = response.data.sysTag
          this.diyTag = response.data.diyTag
          this.jsonData = response.data.floor.split(',')
          this.temp = response.data.findData
          this.couponSelected = response.data.findData.b_d_id
          this.temp.merc_logo = response.data.findData.merc_logo === '' ? undefined : response.data.findData.merc_logo
          this.temp.merc_banner = response.data.findData.merc_banner === '' ? undefined : response.data.findData.merc_banner
          this.temp.merc_wechat_qrcode = response.data.findData.merc_wechat_qrcode === '' ? undefined : response.data.findData.merc_wechat_qrcode
          this.temp.merc_wechat_pub_qrcode = response.data.findData.merc_wechat_pub_qrcode === '' ? undefined : response.data.findData.merc_wechat_pub_qrcode
          this.temp.merc_weshop_photo_qrcode = response.data.findData.merc_weshop_photo_qrcode === '' ? undefined : response.data.findData.merc_weshop_photo_qrcode
          this.temp.merc_video_qrcode = response.data.findData.merc_video_qrcode === '' ? undefined : response.data.findData.merc_video_qrcode
          this.temp.merc_live_qrcode = response.data.findData.merc_live_qrcode === '' ? undefined : response.data.findData.merc_live_qrcode
          this.temp.merc_zan_qrcode = response.data.findData.merc_zan_qrcode === '' ? undefined : response.data.findData.merc_zan_qrcode
          this.temp.merc_sys_tag = response.data.findData.merc_sys_tag.split(',')
          this.temp.merc_diy_tag = response.data.findData.merc_diy_tag.split(',')
        })
      }
    },
    selectData(tag) {
      this.jsonData = {}
      this.jsonData.id = tag
      this.selectName = ''
      this.selectName = this.type[tag - 1].b_d_name
      this.temp.merc_floor = undefined
      this.temp.room_number = undefined
      this.temp.merc_name = undefined
      this.temp.merc_address = ''
      selectfloor(this.jsonData).then(response => {
        this.jsonData = response.data.items
        this.temp.b_d_id = tag
      })
    },
    imgsuccess1(response) {
      this.temp.merc_logo = response.data
    },
    imgsuccess2(response) {
      this.temp.merc_banner.push(response.data)
    },
    imgsuccess3(response) {
      this.temp.merc_wechat_qrcode.push(response.data)
    },
    imgsuccess4(response) {
      this.temp.merc_wechat_pub_qrcode = response.data
    },
    imgsuccess5(response) {
      this.temp.merc_weshop_photo_qrcode = response.data
    },
    imgsuccess6(response) {
      this.temp.merc_video_qrcode = response.data
    },
    imgsuccess7(response) {
      this.temp.merc_live_qrcode = response.data
    },
    imgsuccess8(response) {
      this.temp.merc_zan_qrcode = response.data
    },
    saveaddress() {
      this.temp.merc_address = ''
      this.temp.merc_address += this.selectName
      this.temp.merc_address += (this.temp.merc_floor === undefined) ? '' : this.temp.merc_floor + '楼'
      this.temp.merc_address += (this.temp.room_number === undefined) ? '' : this.temp.room_number
      this.temp.merc_address += (this.temp.merc_name === undefined) ? '' : this.temp.merc_name
    },
    createData() {
      if (this.temp.b_d_id === undefined) {
        this.$message.error('所属商圈为空')
        return false
      }
      if (this.temp.merc_name === undefined) {
        this.$message.error('商家名称为空')
        return false
      }
      if (this.temp.merc_floor === undefined) {
        this.$message.error('楼层为空')
        return false
      }
      if (this.temp.room_number === undefined) {
        this.$message.error('房间号/档口号为空')
        return false
      }
      insertList(this.temp).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
          setTimeout(() => {
            this.$router.push({ path: '/merc/index/list' })
          }, 1.5 * 1000)
        } else {
          this.$message.error('操作失败')
        }
      })
    },
    updateData() {
      this.temp.id = this.listQuery.editid
      updateList(this.temp).then(response => {
        if (response.data === 'success') {
          this.$message.success('操作成功')
          setTimeout(() => {
            this.$router.push({ path: '/merc/index/list' })
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

</style>
