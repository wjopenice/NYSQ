<?php
use Yaf\Application;
use Yaf\Dispatcher;
class MessageController extends BaseGetController
{
    public function init()
    {
        parent::init();
    }

    public function listAction(){
        $userInfo = $this->session->get('userInfo');
        if(empty($userInfo)) ajaxError('用户未登陆','401');
        $id = $userInfo['id'];
        $mess = new messageModel();
        $list = $mess->getList(['user_id'=>$id]);
        ajaxStatus($list);
    }

    public function aboutAction(){
        $data = [
            'img_url'=>'https://dss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=4065216088,3626560770&fm=26&gp=0.jpg',
            'version'=>'v1.0',
            'app_name'=>'逛市场'
        ];
        ajaxStatus($data);
    }
}
