<?php
use Yaf\Application;
use Yaf\Dispatcher;
class FormController extends Yaf\Controller_Abstract {
    public function IndexAction() {
        //1. fetch query
        $post = HttpServer::$post;
        //$post = $this->getRequest()->getPost ("fname");
        //var_dump( $post );
        $content['fname'] = $post["fname"];
        $content['lname'] = $post["lname"];

        //2. fetch model
        $model = new SampleModel();
        //3. assign
        $this->getView()->assign("content", $content);
        //$this->getView()->assign("name", $name);
        $this->display('index');
        //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return FALSE;
    }
    public function FormAction() {
        $get = HttpServer::$get;
        $this->getView()->assign("name", $get['name']);
        $this->display('form');
        return FALSE;
    }

    public function smartyAction()
    {
        /*默认template_dir目录下two/two.tpl*/
        $this->getView()->assign("content", "Hello Hadoop! Welcome to Beijing!<br/>");

        /*指定template_dir目录下的模板*/
        $this->getView()->display('/from/smarty.tpl');

        /*false为禁止显示默认模板   return false表示显示display指定的模板*/
        //return false;
    }

}
