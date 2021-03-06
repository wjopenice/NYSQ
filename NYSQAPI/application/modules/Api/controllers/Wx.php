<?php

use Yaf\Application;
use Yaf\Dispatcher;
class WxController extends BaseGetController
{
    private $appid = '';
    private $appsecret = '';
    protected $accessUrl = '';
    public $token = '';

    public function init()
    {
        parent::init();
        $wechat = Application::app()->getConfig()->wechat;
        $this->appid = $wechat['appid'];
        $this->appsecret = $wechat['appsecret'];
        $this->accessUrl = $wechat['url'];
        if(Application::app()->getConfig()->config['env'] != 'dev') $this->token = $this->access_token($this->appid,$this->appsecret,1);
    }

    public function checkLoginAction(){
        $userInfo = $this->session->get('userInfo');
        if(!empty($userInfo)) ajaxStatus('用户存在');
        ajaxError('用户信息不存在');
    }

    public function delsessionAction(){
        $userInfo = $this->session->del('userInfo');
        if(!empty($userInfo)) ajaxStatus('用户缓存清除成功');
        ajaxError('删除失败');
    }

    public function userAuthAction(){
        if(Application::app()->getConfig()->config['env'] == 'dev'){  //本地测试
            $user = $this->db->field('*')->table('ny_wechat_user')->where('id = 18')->find();
            $user['user_id'] = $user['id'];
            $this->session->set('userInfo',$user);
            $this->status($user['openid']);
        }else {
            $callbackUrl = urlencode($this->accessUrl . "getWebAccessToken");
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . $callbackUrl . "&response_type=code&scope=snsapi_userinfo&state=1234#wechat_redirect";
            header("location:" . $url);
        }
    }

    //2、通过code换取网页授权access_token（与基础支持中的access_token不同）
    public function getWebAccessTokenAction()
    {
        $code = $_GET['code'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->appsecret . "&code=" . $code . "&grant_type=authorization_code";
        $res = _httpPost($url);
        $arr = json_decode($res, true);
        $openid = $arr['openid'];
        $user = $this->db->field("id")
            ->table("wechat_user")
            ->where("openid='{$openid}'")
            ->find();
        if(empty($user)){
            //数据库不存在用户且先关注了公众号才注册用户信息
            $userInfo = getWechatUserInfo($this->token,$openid);
            if(!empty($userInfo) && $userInfo['subscribe'] == 1){
                $this->register($userInfo);
            }else{
                ajaxStatus('请先关注公众号,才可以登陆');
            }
            $this->session->set('userInfo',$user);
        }else{
            $this->session->set('userInfo',$user);
        }
        if (!empty($openid)) $this->status($openid);
        ajaxError('用户信息获取失败');
    }

    public function register($userInfo){
        $map = [
            'openid'=>$userInfo['openid'],
            'subscribe'=>$userInfo['subscribe'],
            'nickname'=>$userInfo['nickname'],
            'sex'=>$userInfo['sex'],
            'headimgurl'=>$userInfo['headimgurl'],
            'city'=>$userInfo['city'],
            'province'=>$userInfo['province'],
            'country'=>$userInfo['country'],
            'subscribe_time'=>$userInfo['subscribe_time'],
            'remark'=>$userInfo['remark'],
            'groupid'=>$userInfo['groupid'],
            'tagid_list'=>json_encode($userInfo['tagid_list']),
            'subscribe_scene'=>$userInfo['subscribe_scene'],
            'qr_scene'=>$userInfo['qr_scene'],
            'qr_scene_str'=>$userInfo['qr_scene_str'],
            'created_time'=>date('Y-m-d H:i:s'),
            'updated_time'=>date('Y-m-d H:i:s'),
        ];
        $res = $this->db->action($this->db->insertSql('wechat_user',$map));
        echo json_encode($res);die;
    }

    //授权后的token能获取的少量信息
    public function registerMin($userInfo){
        $map = [
            'openid'=>$userInfo['openid'],
            'nickname'=>$userInfo['nickname'],
            'sex'=>$userInfo['sex'],
            'headimgurl'=>$userInfo['headimgurl'],
            'city'=>$userInfo['city'],
            'province'=>$userInfo['province'],
            'country'=>$userInfo['country'],
            'created_time'=>date('Y-m-d H:i:s'),
            'updated_time'=>date('Y-m-d H:i:s'),
        ];
        $res = $this->db->action($this->db->insertSql('wechat_user',$map));
        echo json_encode($res);die;
    }

    //授权后的token获取用户信息，不是基础token关注的用户
    public function getUerInfo($token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$openid}&lang=zh_CN";
        $res = _httpPost($url);
        return $res;
    }

    public function getToken(){
        $referer = $_GET['url'];//$_SERVER['HTTP_REFERER'];
        $preUrl = base64_encode($referer);
        $callbackUrl=urlencode("http://yt.xiaolongsu.cn/api/wx/createOpenid?preUrl={$preUrl}");
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$callbackUrl."&response_type=code&scope=snsapi_base&state=1234#wechat_redirect";
        header("location:".$url);
    }

    public function access_token($appid,$appsecret,$gzh_id){
        $access_token = $this->db->field("*")
            ->table("ny_access_token")
            ->where('gzh_id='.$gzh_id)
            ->find();
        if(!empty($access_token)){
            $id = $access_token['id'];
            //access_token 不为空
            $check_access_token = check_access_token($access_token);
            if(!$check_access_token){
                //过期了重新获取
                $newsAccessToken=get_access_tokenApi($appid,$appsecret);
                if(!empty($newsAccessToken['access_token'])){
                    //获取正常  进行存储
                    $access_token = $newsAccessToken['access_token'];
                    $data = array(
                        'access_token'=>$access_token,
                        'time'=>time(),
                        'expires_in'=>$newsAccessToken['expires_in'],
                        'gzh_id'=>$gzh_id,
                        'id'=>$id
                    );
                    $this->db->action($this->db->updateSql('ny_access_token',$data,'id='.$id));
                    $getAccessToken = $newsAccessToken['access_token'];
                }else{
                    ajaxError('token过期重新获取失败'.$newsAccessToken['errmsg']);
                }
            }else{
                //没有过期  直接返回
                $getAccessToken=$access_token['access_token'];
            }


        }else{
            //为空
            //获取三次  不成功则继续
            for ($i=0; $i < 3; $i++) {
                $res = get_access_tokenApi($appid,$appsecret);
                if(!empty($res['access_token'])){
                    //获取正常  进行存储
                    $access_token = $res['access_token'];
                    $data = array(
                        'access_token'=>$access_token,
                        'time'=>time(),
                        'expires_in'=>$res['expires_in'],
                        'gzh_id'=>$gzh_id,
                    );
                    $this->db->action($this->db->insertSql('ny_access_token',$data));
                    $getAccessToken = $res['access_token'];
                    break;
                }else{
                    //出错
                    $getAccessToken = $res;
                }
            }
        }
        return $getAccessToken;
    }

}
