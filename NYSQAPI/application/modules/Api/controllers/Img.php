<?php
use Yaf\Application;
use Yaf\Dispatcher;
class ImgController extends BaseGetController
{
    public function init()
    {
        parent::init();
    }

    public function addAction()
    {
         $img = new imgModel();
         $res = $img->upload();
         if(!empty($res)) ajaxStatus($res);
         ajaxError('上传失败');
    }

    public function renameAction(){
        $id = I('get.id');
        $intro = I('get.name');
        if(empty($id)) ajaxStatus('请先上传图片');
        $img = new imgModel();
        $res = $img->rename(['id'=>$id,'intro'=>$intro]);
        if(!empty($res)) ajaxStatus('修改成功');
        ajaxError('修改失败');
    }
}
