(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-015836da"],{1455:function(e,t,i){"use strict";i.r(t);var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"app-container",attrs:{span:12}},[i("el-form",{ref:"form",staticClass:"div-form",attrs:{model:e.form,"label-width":"80px"}},[i("el-form-item",{attrs:{label:"运营商名称"}},[i("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),i("el-form-item",{attrs:{label:"运营商公司名称"}},[i("el-input",{model:{value:e.form.company_name,callback:function(t){e.$set(e.form,"company_name",t)},expression:"form.company_name"}})],1),e._v(" "),i("el-form-item",{attrs:{label:"地址"}},[i("el-input",{model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}})],1),e._v(" "),i("el-form-item",{attrs:{label:"手机"}},[i("el-input",{model:{value:e.form.phone,callback:function(t){e.$set(e.form,"phone",t)},expression:"form.phone"}})],1),e._v(" "),i("el-form-item",{attrs:{label:"经纬度"}},[i("el-input",{model:{value:e.form.longlatitude,callback:function(t){e.$set(e.form,"longlatitude",t)},expression:"form.longlatitude"}})],1),e._v(" "),i("el-form-item",{attrs:{label:"视频链接地址"}},[i("el-input",{model:{value:e.form.video_url,callback:function(t){e.$set(e.form,"video_url",t)},expression:"form.video_url"}})],1),e._v(" "),i("el-form-item",{attrs:{label:"上传轮播图"}},[i("el-upload",{attrs:{action:e.local_upload_url,"on-success":e.uploadBanner,"on-remove":e.onRemoveBanner,"file-list":e.bannerList,"list-type":"picture-card",icon:"el-icon-upload",limit:9,"auto-upload":!0}},[e._v("上传图片(最多9张)")])],1),e._v(" "),i("el-form-item",{attrs:{label:"上传二维码图"}},[i("el-upload",{attrs:{action:e.local_upload_url,"on-success":e.uploadQrcode,"on-remove":e.onRemoveQrcode,"file-list":e.qrcodeList,"list-type":"picture-card",icon:"el-icon-upload",limit:9,"auto-upload":!0}},[e._v("上传图片(最多9张)")])],1),e._v(" "),i("el-form-item",{attrs:{label:"选择标签"}},[i("el-checkbox-group",{model:{value:e.form.tag_ids,callback:function(t){e.$set(e.form,"tag_ids",t)},expression:"form.tag_ids"}},e._l(e.tagList,(function(t){return i("el-checkbox",{key:t.id,attrs:{label:t.id}},[e._v(e._s(t.b_d_name))])})),1)],1),e._v(" "),i("el-form-item",[i("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[0==e.form.id?i("label",[e._v("立即创建")]):i("label",[e._v("修改")])]),e._v(" "),i("router-link",{attrs:{to:"list"}},[i("el-button",[e._v("返回")])],1)],1)],1)],1)},o=[],n=(i("7f7f"),i("96bd")),s=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{ref:e.id,staticClass:"dropzone",attrs:{id:e.id,action:e.url}},[i("input",{attrs:{type:"file",name:"file"}})])},r=[],l=(i("c5f6"),i("79e3")),d=i.n(l);i("7bc1");d.a.autoDiscover=!1;var c={props:{id:{type:String,required:!0},url:{type:String,required:!0},clickable:{type:Boolean,default:!0},defaultMsg:{type:String,default:"上传图片"},acceptedFiles:{type:String,default:""},thumbnailHeight:{type:Number,default:200},thumbnailWidth:{type:Number,default:200},showRemoveLink:{type:Boolean,default:!0},maxFilesize:{type:Number,default:2},maxFiles:{type:Number,default:3},autoProcessQueue:{type:Boolean,default:!0},useCustomDropzoneOptions:{type:Boolean,default:!1},defaultImg:{default:"",type:[String,Array]},couldPaste:{type:Boolean,default:!1}},data:function(){return{dropzone:"",initOnce:!0}},watch:{defaultImg:function(e){0!==e.length?this.initOnce&&(this.initImages(e),this.initOnce=!1):this.initOnce=!1}},mounted:function(){var e=document.getElementById(this.id),t=this;this.dropzone=new d.a(e,{clickable:this.clickable,thumbnailWidth:this.thumbnailWidth,thumbnailHeight:this.thumbnailHeight,maxFiles:this.maxFiles,maxFilesize:this.maxFilesize,dictRemoveFile:"Remove",addRemoveLinks:this.showRemoveLink,acceptedFiles:this.acceptedFiles,autoProcessQueue:this.autoProcessQueue,dictDefaultMessage:'<i style="margin-top: 3em;display: inline-block" class="material-icons">'+this.defaultMsg+"</i><br>Drop files here to upload",dictMaxFilesExceeded:"只能一个图",previewTemplate:'<div class="dz-preview dz-file-preview">  <div class="dz-image" style="width:'+this.thumbnailWidth+"px;height:"+this.thumbnailHeight+'px" ><img style="width:'+this.thumbnailWidth+"px;height:"+this.thumbnailHeight+'px" data-dz-thumbnail /></div>  <div class="dz-details"><div class="dz-size"><span data-dz-size></span></div> <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>  <div class="dz-error-message"><span data-dz-errormessage></span></div>  <div class="dz-success-mark"> <i class="material-icons">done</i> </div>  <div class="dz-error-mark"><i class="material-icons">error</i></div></div>',init:function(){var e=this,i=t.defaultImg;if(i)if(Array.isArray(i)){if(0===i.length)return;i.map((function(i,a){var o={name:"name"+a,size:12345,url:i};return e.options.addedfile.call(e,o),e.options.thumbnail.call(e,o,i),o.previewElement.classList.add("dz-success"),o.previewElement.classList.add("dz-complete"),t.initOnce=!1,!0}))}else{var a={name:"name",size:12345,url:i};this.options.addedfile.call(this,a),this.options.thumbnail.call(this,a,i),a.previewElement.classList.add("dz-success"),a.previewElement.classList.add("dz-complete"),t.initOnce=!1}},accept:function(e,t){t()},sending:function(e,i,a){t.initOnce=!1}}),this.couldPaste&&document.addEventListener("paste",this.pasteImg),this.dropzone.on("success",(function(e){t.$emit("dropzone-success",e,t.dropzone.element)})),this.dropzone.on("addedfile",(function(e){t.$emit("dropzone-fileAdded",e)})),this.dropzone.on("removedfile",(function(e){t.$emit("dropzone-removedFile",e)})),this.dropzone.on("error",(function(e,i,a){t.$emit("dropzone-error",e,i,a)})),this.dropzone.on("successmultiple",(function(e,i,a){t.$emit("dropzone-successmultiple",e,i,a)}))},destroyed:function(){document.removeEventListener("paste",this.pasteImg),this.dropzone.destroy()},methods:{removeAllFiles:function(){this.dropzone.removeAllFiles(!0)},processQueue:function(){this.dropzone.processQueue()},pasteImg:function(e){var t=(e.clipboardData||e.originalEvent.clipboardData).items;"file"===t[0].kind&&this.dropzone.addFile(t[0].getAsFile())},initImages:function(e){var t=this;if(e)if(Array.isArray(e))e.map((function(e,i){var a={name:"name"+i,size:12345,url:e};return t.dropzone.options.addedfile.call(t.dropzone,a),t.dropzone.options.thumbnail.call(t.dropzone,a,e),a.previewElement.classList.add("dz-success"),a.previewElement.classList.add("dz-complete"),!0}));else{var i={name:"name",size:12345,url:e};this.dropzone.options.addedfile.call(this.dropzone,i),this.dropzone.options.thumbnail.call(this.dropzone,i,e),i.previewElement.classList.add("dz-success"),i.previewElement.classList.add("dz-complete")}}}},u=c,m=(i("1b95"),i("2877")),p=Object(m["a"])(u,s,r,!1,null,"2bb8ff5e",null),f=p.exports,h={name:"ArticleList",components:{Dropzone:f},filters:{statusFilter:function(e){var t={published:"success",draft:"info",deleted:"danger"};return t[e]},tagName:function(e){return this.tagList[e]}},data:function(){return{uploadUri:this.GLOBAL.upload_url,local_upload_url:this.GLOBAL.local_upload_url,tagList:[],bannerList:[],qrcodeList:[],form:{name:"",company_name:"",address:"",phone:"",longlatitude:"",latitude:"",video_url:"",banner_ids:[],qrcode_img_ids:[],tag_ids:[],id:0}}},created:function(){"undefined"!==typeof this.$route.query.id&&(this.form.id=this.$route.query.id),this.getTagInfo(),this.form.id>0&&this.getInfo()},methods:{onSubmit:function(){var e=this;console.log("submit!"),this.listLoading=!0,Object(n["a"])(this.form).then((function(t){console.log(t),"success"===t.msg?e.$message.success(t.data):e.$message.error(t.data),e.listLoading=!1}))},uploadBanner:function(e){this.form.banner_ids.push(parseInt(e.data.id))},uploadQrcode:function(e){console.log(e),this.form.qrcode_img_ids.push(e.data.id)},onRemoveBanner:function(e){console.log(e);var t=parseInt(e.id);this.form.banner_ids.splice(this.form.banner_ids.indexOf(t),1)},onRemoveQrcode:function(e){console.log(e);var t=parseInt(e.id);this.form.qrcode_img_ids.splice(this.form.qrcode_img_ids.indexOf(t)+1,1)},getTagInfo:function(){var e=this;this.req={},this.listLoading=!0,Object(n["d"])(this.req).then((function(t){e.tagList=t.data,e.listLoading=!1}))},getInfo:function(){var e=this;this.req={},this.listLoading=!0,this.req.id=this.$route.query.id,Object(n["e"])(this.req).then((function(t){console.log(t.data),e.form.name=t.data.name,e.form.company_name=t.data.company_name,e.form.address=t.data.address,e.form.longlatitude=t.data.longitude+","+t.data.latitude,e.form.phone=t.data.phone,e.form.banner_ids=t.data.banner_ids,e.form.qrcode_img_ids=t.data.qrcode_img_ids,e.form.video_url=t.data.video_url,e.form.tag_ids=t.data.tag_ids,e.bannerList=t.data.bannerList,e.qrcodeList=t.data.qrcodeList,e.listLoading=!1}))}}},v=h,g=i("530e"),b=i.n(g),_=Object(m["a"])(v,a,o,!1,null,null,null);"function"===typeof b.a&&b()(_);t["default"]=_.exports},"1b95":function(e,t,i){"use strict";var a=i("4e00"),o=i.n(a);o.a},"4e00":function(e,t,i){},"530e":function(e,t){},"96bd":function(e,t,i){"use strict";i.d(t,"c",(function(){return o})),i.d(t,"a",(function(){return n})),i.d(t,"b",(function(){return s})),i.d(t,"e",(function(){return r})),i.d(t,"d",(function(){return l}));var a=i("b775");function o(e){return Object(a["a"])({url:"/operator/list",method:"get",params:e})}function n(e){return Object(a["a"])({url:"/operator/add",method:"get",params:e})}function s(e){return Object(a["a"])({url:"/operator/del",method:"get",params:e})}function r(e){return Object(a["a"])({url:"/operator/getInfo",method:"get",params:e})}function l(e){return Object(a["a"])({url:"/businessdistrict/getListByOpid",method:"get",params:e})}}}]);