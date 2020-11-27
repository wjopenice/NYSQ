<?php

use Yaf\Application;
use Yaf\Dispatcher;
class UserController extends BaseGetController
{
    public $userInfo = [];

    public function init()
    {
        parent::init();
        if(empty($this->session->get('userInfo'))) ajaxError('用户未登陆',401);
        $this->userInfo = $this->session->get('userInfo');
    }

    //个人中心首页
    public function indexAction(){
        $data = [
            'userInfo'=>[
                'nickname'=>$this->userInfo['nickname'],
                'headimgurl'=>$this->userInfo['headimgurl'],
                'id'=>$this->userInfo['id'],
                'competence'=>'普通用户',  //后面要改成真实数据
                'province'=>$this->userInfo['province'],
                'city'=>$this->userInfo['city']
            ],
            'statistics'=>[   //数据后面要改为真实的
                'by_browse_num'=>5554, //ny_user_follow
                'by_attention_num'=>50,
                'by_collect_num'=>150,  //普通用户没有此内容
                'by_share_num'=>150,   //普通用户没有此内容
                'browse_num'=>111,
                'attention_num'=>222, //ny_user_follow
                'collect_num'=>333, //ny_merc_user_collect
                'share_num'=>444,
            ]
        ];
        $this->status($data);
    }

    //用户设置
    public function editUserAction(){
        if(IS_GET) {
            $id = $this->userInfo['id'];
            $headimgurl = I('get.headimgurl/s');
            $nickname = I('get.nickname/s');
            $sex = I('get.sex/d');
            $age = I('get.age/d');
            $signature = I('get.signature/s');
            $province = I('get.province/s');
            $city = I('get.city/s');
            $map['id'] = $id;
            if (!empty($nickname)) $map['nickname'] = $nickname;
            if (!empty($headimgurl)) $map['headimgurl'] = $headimgurl;
            if (!empty($sex)) $map['sex'] = $sex;
            if (!empty($age)) $map['age'] = $age;
            if (!empty($signature)) $map['signature'] = $signature;
            if (!empty($province)) $map['province'] = $province;
            if (!empty($city)) $map['city'] = $city;
            $res = $this->db->action($this->db->insertSql('wechat_user', $map));
            if (!empty($res)) $this->status('修改成功');
            $this->error('修改失败');
        }
    }

    //获取用户信息
    public function getUserInfoAction(){
        $this->status($this->userInfo);
    }

    public function bindWechatAction(){
        if(IS_GET){
            $id = $this->userInfo['id'];
            $wechat = I('get.wechat/s');
            $res = $this->db->action($this->db->updateSql('wechat_user',['wechat'=>$wechat],'id='.$id));
            if(!empty($res)) ajaxStatus('微信绑定成功');
            ajaxError('微信绑定失败');
        }
    }

    public function bingPhoneAction(){
        if(IS_GET){
            $id = $this->userInfo['id'];
            $phone = I('get.phone/s');
            $res = $this->db->action($this->db->updateSql('wechat_user',['phone'=>$phone],'id='.$id));
            if(!empty($res)) ajaxStatus('手机号绑定成功');
            ajaxError('手机号绑定失败');
        }
    }
    //我的关注列表
    public function userfollowAction(){
        $user_id = $this->userInfo['user_id'];
        $userData = $this->db->field('follow_user_id')
            ->table("ny_user_follow")
            ->where("user_id = {$user_id}")
            ->select();
        if(!empty($userData)){
            $tagList = array_column($userData,'follow_user_id');
            $strData = implode(",",$tagList);
            $result= $this->db->field('id as user_id,headimgurl,nickname')
                ->table("ny_wechat_user")
                ->where("id IN ({$strData})")
                ->select();
            $res = $result;
        }else{
            $res = [];
        }
        $this->status($res);
    }

    //添加/取消关注
    public function follwAction(){
        $user_id = $this->userInfo['user_id'];
        $follow_user_id = I('post.follow_user_id/d');
        $where = "user_id = {$user_id} AND follow_user_id = {$follow_user_id}";
        $arrData = $this->db->field('user_id,follow_user_id')
            ->table("ny_user_follow")
            ->where($where)
            ->find();
        if(!empty($arrData['user_id'])){
            $this->db->action($this->db->deleteSql("user_follow",$where));
        }else{
            $data['user_id'] = $user_id;
            $data['follow_user_id'] = $follow_user_id;
            $this->db->action($this->db->insertSql("user_follow",$data));
        }
        $this->status_nodata();
    }
}
