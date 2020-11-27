<template>
  <div class="app-container">
    <div class="filter-container">
      <el-form ref="dataForm" :rules="rules" :model="list" label-position="left" label-width="110px" style="width: 400px; margin-left:50px;">
        <el-form-item label="网站标题">
          <el-input v-model="list.title" />
        </el-form-item>
        <el-form-item label="网站logo">
          <el-button type="primary" icon="el-icon-upload" style="" @click="imagecropperShow=true">
            编辑头像
          </el-button>
          <pan-thumb :image="list.logo" style="width: 50px; height: 50px; float: right;"  />
          <image-cropper
            v-show="imagecropperShow"
            :key="imagecropperKey"
            :width="300"
            :height="300"
            :url="uploadUri"
            lang-type="en"
            @close="close"
            @crop-upload-success="cropSuccess"
          />
        </el-form-item>
        <el-form-item label="网站关键词">
          <el-input v-model="list.keywords" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" placeholder="Please input" />
        </el-form-item>
        <el-form-item label="网站描述">
          <el-input v-model="list.description" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" placeholder="Please input" />
        </el-form-item>
        <el-form-item label="版权信息">
          <el-input v-model="list.copyright_information" />
        </el-form-item>
        <el-form-item label="ICP备案">
          <el-input v-model="list.icp" />
        </el-form-item>
        <el-form-item label="网安备案">
          <el-input v-model="list.network_security_record" />
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
import { createArticle, updateArticle } from '@/api/article'
import { fetchSettingIndex } from '@/api/setting'
import ImageCropper from '@/components/ImageCropper'
import PanThumb from '@/components/PanThumb'
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
  name: 'SettingIndex',
  components: { ImageCropper, PanThumb },
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
      imagecropperShow: false,
      uploadUri: this.GLOBAL.upload_url,
      imagecropperKey: 0,
      image: 'https://wpimg.wallstcn.com/577965b9-bb9e-4e02-9f0c-095b41417191',
      calendarTypeOptions,
      listLoading: true,
      dialogStatus: '',
      statusOptions: ['published', 'draft', 'deleted'],
      list: {
        id: '',
        title: '',
        logo: '',
        keywords: '',
        description: '',
        copyright_information: '',
        icp: '',
        network_security_record: ''
      },
      textMap: {
        update: 'Edit',
        create: 'Create'
      },
      rules: {
        type: [{ required: true, message: 'type is required', trigger: 'change' }],
        timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      }
    }
  },
  created() {
    this.getList()
  },
  methods: {
    cropSuccess(resData) {
      this.imagecropperShow = false
      this.imagecropperKey = this.imagecropperKey + 1
      this.image = resData.files.avatar
    },
    close() {
      this.imagecropperShow = false
    },
    getList() {
      this.listLoading = true
      fetchSettingIndex(this.listQuery).then(response => {
        this.list = response.data.items
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
    }
  }
}
</script>

<style lang="scss" scoped>
  .avatar{
    width: 200px;
    height: 200px;
    border-radius: 50%;
  }
  .dashboard {
    &-container {
      margin: 30px;
    }
    &-text {
      font-size: 30px;
      line-height: 46px;
    }
  }

</style>

