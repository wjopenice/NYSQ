<?php
use Yaf\Application;
use Yaf\Dispatcher;
class IndexController extends Yaf\Controller_Abstract {
    public $db;
    const APP_KEY = "MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=";
    public function init(){
        Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token');
        header('Access-Control-Allow-Method: POST, GET');//允许访问的方式
        if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
            exit;
        }
        $this->db = new dbModel();
    }

    public function indexAction(){
        die("API START");
    }

    public function index2Action(){
        die("API START index2");
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>
