<?php

use Yaf\Application;
use Yaf\Dispatcher;
class OperatorController extends BaseController
{
    public $db;
    public $imgHost;

    public function init(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        \Yaf\Dispatcher::getInstance()->autoRender(false);
        $this->db = new dbModel();
        $this->imgHost = Application::app()->getConfig()->application['img_host'];
    }

    public function testAction(){
        $map = [
            'name'=>'dd',
            'company_name'=>'bb',
            'tag_ids'=>'1,2',
            'banner_ids'=>'2,3',
            'qrcode_img_ids'=>'4,5',
            'address'=>'sss',
            'longitude'=>22.33,
            'latitude'=>11.44,
            'phones'=>15994205524,
            'video_id'=>1,
            'video_url'=>'http://baidu.com',
        ];
        $oper = new operatorModel();
        $res = $oper->addBackups('dd','bb','1,2','3,4','4,5','sss',11.22,22.33,15994205524,12,'http://baidu.com');
        var_dump($res);die;
        array_map(function ($n){return "'".$n."'";},['1 ','2 ']);
        $res = Application::app()->getConfig()->application['img_host'];
        echo $res;die;
        echo '<pre>';
        var_dump($res['img_host']);
    }

    public function listAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $arr = $this->db->field("*")
            ->table("ny_operator")
            ->limit($start,$limit)
            ->select();
        $imgList = $this->db->field('id,name,url')
            ->table('ny_img')
            ->select();
        $bus = new businessdistrictModel();
        $businessDistrList = $bus->getList();
        foreach ($arr as &$v){
            $v['tag_id_arr'] = explode(',',$v['tag_ids']);
            $v['img_id_arr'] = explode(',',$v['banner_ids']);
            $v['qrcode_id_arr'] = explode(',',$v['qrcode_img_ids']);
        }
        foreach ($imgList as &$vo){
            if(!preg_match("/^(http)/",$vo['url'])) $vo['url'] = $this->imgHost.$vo['url'];
        }
        $res['busList'] = array_column($businessDistrList,'b_d_name','id');
        $res['imgList'] = array_column($imgList,'url','id');
        $res['total'] = count($arr);
        $res['items'] = array_slice($arr,$start,$limit) ;
        $this->status($res);
        echo json_encode($res);
    }

    public function addAction(){
        if($this->getRequest()->isGet()){
            $id = I('get.id/d');
            $name = I('get.name/s');
            $company_name = I('get.company_name/s');
            $tag_ids = I('get.tag_ids/a');
            $banner_ids = I('get.banner_ids/a');
            $qrcode_img_ids = I('get.qrcode_img_ids/a');
            $address = I('get.address');
            $longlatitude = I('get.longlatitude/s');
            $longitude = I('get.longitude/f');
            $latitude = I('get.latitude/f');
            $phone = I('get.phone/s');
            $video_id = I('get.video_id/d',0);
            $video_url = I('get.video_url/s');
            if(!empty($longlatitude)) list($longitude,$latitude) = explode(',',$longlatitude);
            $sql = "select id,`name` from ny_operator where `name`=? ";
            if(!empty($id)) $operator = $this->db->stmtFetch($sql,[$name]);
            if(!empty($operator) && empty($id)) $this->error('该运营商名称已存在');
            $oper = new operatorModel();
            $map = [
                'name'=>$name,
                'company_name'=>$company_name,
                'tag_ids'=>empty($tag_ids)?'':join(',',$tag_ids),
                'banner_ids'=>empty($banner_ids)?'':join(',',$banner_ids),
                'qrcode_img_ids'=>empty($qrcode_img_ids)?'':join(',',$qrcode_img_ids),
                'address'=>$address,
                'longitude'=>$longitude,
                'latitude'=>$latitude,
                'phones'=>$phone,
                'video_id'=>$video_id,
                'video_url'=>$video_url,
                'created_time'=>date('Y-m-d H:i:s'),
                'updated_time'=>date('Y-m-d H:i:s')
            ];
            if(empty($id)) {
                $res = $this->db->action($this->db->insertSql('operator', $map));
                if (!empty($res)) {
                    $oper->addBackups($map);
                    $this->status('添加成功');
                }
                $this->error('添加失败');
            }else{
                unset($map['created_time']);
                $res = $this->db->action($this->db->updateSql('operator', $map,'id='.$id));
                if (!empty($res)){
                    $map['id'] = $id;
                    $map['old_name'] = $operator['name'];
                    $oper->addBackups($map);
                    $this->status('修改成功');
                }
                $this->error('修改失败');
            }

        }
    }

    public function delAction(){
        $id = I('get.id/d');
        if($this->getRequest()->isGet()){
            $oper = $this->db->field('id')
                ->table('ny_operator')
                ->find();
            if(empty($oper)) $this->error('已删除');
            $res = $this->db->action($this->db->deleteSql('operator','id ='.$id));
            if(!empty($res)) $this->status('删除成功');
            $this->error('删除失败');
        }
    }

    public function getInfoAction(){
        $id = I('get.id/d');
        if(empty($id)) $this->status([]);
        $oper = $this->db->field('id,`name`,company_name,tag_ids,banner_ids,address,longitude,latitude,phones,qrcode_img_ids,video_id,video_url,browse_num')
            ->table('ny_operator')
            ->where(" id =".$id)
            ->find();
        if(!empty($oper)){
            $ids = $oper['banner_ids'].','.$oper['qrcode_img_ids'];
            $imgList = [];
            if(preg_match('/\d+/',$ids)) {
                if(preg_match('/^,\d+/',$ids)) $ids = substr($ids,1);
                if(preg_match('/,$/',$ids)) $ids = substr($ids,0,-1);
                $imgList = $this->db->field('id,name,url')
                    ->table('ny_img')
                    ->where("`type`=1 and id in({$ids})")
                    ->select();
            }
            foreach ($imgList as &$v){
                if(!preg_match("/^(http)/",$v['url'])) $v['url'] = $this->imgHost.$v['url'];
                if(!empty($oper['banner_ids']) && in_array($v['id'],explode(',',$oper['banner_ids']))) {
                    $oper['bannerList'][] = ['id'=>$v['id'],'name' => $v['name'], 'url' => $v['url']];
                }else{
                    $oper['qrcodeList'][] = ['id'=>$v['id'],'name' => $v['name'], 'url' => $v['url']];
                }
            }
            $oper['tag_ids'] = array_map(function ($n){return $n."";},explode(',',$oper['tag_ids']));
            $oper['banner_ids'] = explode(',',$oper['banner_ids']);
            $oper['qrcode_img_ids'] = explode(',',$oper['qrcode_img_ids']);
            $this->status($oper);
        }else{
            $this->status([]);
        }
    }

    public function getTagInfoAction(){
        $id = I('get.id/d',1);
        $tagList = $this->db->field('id,name')
            ->table('ny_tag')
            ->where(" `type`=1 and operator_id =".$id)
            ->select();
        $this->status($tagList);
    }

}
