<?php
use Yaf\Application;
use Yaf\Dispatcher;
class LoginController extends BaseController {
    const APP_KEY = "MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=";
    public function init(){
        parent::init();
    }
    //VUE ADMIN API STATUS
    public function status($res){
        $result['code'] = 20000;
        $result['msg'] = 'success';
        $result['data'] = $res;
        echo json_encode($result,320);
        exit;
    }
    public function errors($res){
        $result['code'] = 50000;
        $result['msg'] = 'error';
        $result['data'] = $res;
        echo json_encode($result,320);
        exit;
    }
    //Administrator login
    public function indexAction() {
        if($this->getRequest()->isPost()){
            Dispatcher::getInstance()->autoRender(false);
            $resdata = json_decode($this->data);
            $u = addslashes($resdata->username);
            $p = $this->hmac256(addslashes($resdata->password));
            $result = $this->db->field("*")->table("ny_system")->where("username = '{$u}' and password = '{$p}'")->find();
            if($u == $result['username'] && $p == $result['password']){
                $data['login_time'] = time();
                $data['ip'] = server("REMOTE_ADDR");
                $data['log_type'] = "后台登录";
                $data['log_info'] = "管理员后台登录";
                $this->db->action($this->db->updateSql("system", $data, " username = '{$u}'"));
                $res = ["token"=>"admin-token"];
                $this->status($res);
            }else{
                $res = ["token"=>"admin-token"];
                $this->errors($res);
            }
        }
    }

    public function logoutAction(){
        Dispatcher::getInstance()->autoRender(false);
        $resdata = json_decode($this->data);
        $this->status($resdata);
    }

    public function hmac256($data){
        return hash_hmac("sha256",$data,self::APP_KEY);
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>
