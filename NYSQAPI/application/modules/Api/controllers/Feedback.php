<?php

use Yaf\Application;
use Yaf\Dispatcher;
class FeedbackController extends BaseGetController
{
    public function init()
    {
       parent::init();
    }

    public function addAction(){
        if(IS_POST) {
            $userInfo = $this->session->get('userInfo');
            if (empty($userInfo)) ajaxError('用户未登陆', '401');
            $content = I('post.content/s');
            $phone = I('post.phone/s');
            $map = [
                'content' => $content,
                'phone' => $phone,
                'user_id' => $userInfo['id'],
                'user_name' => $userInfo['nickname'],   //后面用session来取
                'created_time' => date('Y-m-d H:i:s'),
                'updated_time' => date('Y-m-d H:i:s'),
            ];
            $res = $this->db->action($this->db->insertSql('feedback', $map));
            if (!empty($res)) ajaxStatus('提交成功');
            ajaxError('提交失败');
        }
    }
}
