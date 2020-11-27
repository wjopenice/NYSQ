<?php
use Yaf\Application;
use Yaf\Dispatcher;
class OperatorController extends BaseGetController
{
    public $userInfo = [];

    public function init()
    {
        parent::init();
        if(empty($this->session->get('userInfo'))) ajaxError('用户未登陆',401);
        $this->userInfo = $this->session->get('userInfo');
    }

    public function indexAction()
    {
        $id = get('id');
        $data = ['operatorInfo' => [], 'tagList' => [], 'videoInfo' => [], 'bannerList' => [], 'qrcodeList' => [], 'articleList' => [], 'storeList' => []];
        $operator = $this->db->field('id,name,company_name,address,tag_ids,banner_ids,longitude,latitude,phone,video_id,browse_num,qrcode_img_ids')
            ->table('ny_operator')
            ->where("id=" . $id)
            ->find();
        if (!empty($operator)) {
            $data['operatorInfo'] = $operator;
            $bus = new businessdistrictModel();
            $businessDistrList = $bus->getList(['ids'=>$operator['tag_ids']]);
            foreach ($businessDistrList as &$v){
                $v['name'] = $v['b_d_name'];
            }
            if (!empty($businessDistrList)) $data['tagList'] = $businessDistrList;
            $video = $this->db->field('id,url,name')
                ->table('ny_video')
                ->where('id=' . $operator['video_id'] . ' and type=1 ')
                ->find();
            if (!empty($video)) $data['videoInfo'] = $video;
            $imgIdArr = array_merge(explode(',', $operator['banner_ids']), explode(',', $operator['qrcode_img_ids']));
            $imgList = $this->db->field('id,name,url')
                ->table('ny_img')
                ->where(" id in (" . join(',', $imgIdArr) . ") and `type` in (1,2) ")
                ->select();
            foreach ($imgList as $v) {
                if (in_array($v['id'], explode(',', $operator['banner_ids']))) {
                    $data['bannerList'][] = $v;
                } else {
                    $data['qrcodeList'][] = $v;
                }
            }
            if (!empty($imgList)) $data['bannerList'] = $imgList;
        }
        $articleList = $this->db->field('id,title,pic,`describe`')
            ->table('ny_article')
            ->order()
            ->limit(0, 5)
            ->select();
        $mercList = $this->db->field('*')
            ->table('ny_merc')
            ->order()
            ->limit(0, 5)
            ->select();

        $data['articleList'] = $articleList;
        $data['mercList'] = $mercList;
        echo json_encode($data);
    }

    public function getInfoAction(){
        $om = new operatorModel();
        if(empty($this->userInfo['operator_id'])){
            $id = $this->userInfo['operator_id'];
            $operator = $om->getInfo(['id'=>$id]);
            $data = [
                'id' =>$id,
                'name'=>$operator['name'],
                'company_name'=>$operator['company_name'],
                'address'=>$operator['address'],
                'wechat_num'=>isset($operator['qrcode_img_ids'])?count(explode(',',$operator['qrcode_img_ids'])):0,
                'phone_num'=>isset($operator['phone'])?count(explode(',',$operator['phone'])):0,
                'banner_num'=>isset($operator['banner_ids'])?count(explode(',',$operator['banner_ids'])):0,
                'is_video'=>(isset($operator['video_id']) || isset($operator['video_url']))?'已上传':'未上传'
            ];
        }else{
            $data = [
                'id'=>0,
                'name'=>'',
                'company_name'=>'',
                'address'=>'',
                'wechat_num'=>0,
                'phone_num'=>0,
                'banner_num'=>0,
                'is_video'=>'未上传'
            ];
        }
        ajaxStatus($data);
    }

    public function editAction(){
        $name = I('get.name/s');
        $companyName = I('get.company_name/s');
        $address = I('get.address/s');
        $bannerIds= I('get.banner_ids/s');
        $videoUrl= I('get.video_url/s');
        $phones= I('get.phones/s');
        $map = [];
        if (!empty($name)) $map['name'] = $name;
        if (!empty($companyName)) $map['company_name'] = $companyName;
        if (!empty($address)) $map['address'] = $address;
        if (!empty($bannerIds)) $map['banner_ids'] = $bannerIds;
        if (!empty($videoUrl)) $map['video_url'] = $videoUrl;
        if (!empty($phones)) $map['phones'] = $phones;
        if(empty($this->userInfo['operator_id'])){
            $res = $this->db->action($this->db->insertSql('operator', $map));
            if ($res){
                $operatorId =  $this->db->getInsertId();
                $userInfo = $this->userInfo;
                $userInfo['operator_id'] = $operatorId;
                $this->db->action($this->db->updateSql('wechat_user', ['operator_id'=>$operatorId], 'id=' . $userInfo['id']));
                $this->session->del('userInfo');
                $this->session->set('userInfo',$userInfo);
                ajaxStatus(['id'=>$this->db->getInsertId()]);
            }
            ajaxError('添加失败');
        }else {
            $id = $this->userInfo['operator_id'];
            $res = $this->db->action($this->db->updateSql('operator', $map, 'id=' . $id));
            if ($res) ajaxStatus('编辑成功');
            ajaxError('编辑失败');
        }
    }

    //通信地址接口
    public function getAddress(){

    }

    public function wechatListAction(){
        $data = [];
        $imgList = [];
        if(empty($this->userInfo['operator_id'])) ajaxError('运营商不存在');
        $id = $this->userInfo['operator_id'];
        $om = new operatorModel();
        $img = new imgModel();
        $operator = $om->getInfo(['id'=>$id]);
        if(empty($operator)) ajaxError('运营商不存在');
        if(!empty($operator['qrcode_img_ids'])) $imgList = $img->imgList(['ids'=>$operator['qrcode_img_ids']]);
        foreach ($imgList as $k=>$v){
            $data[] = [
                'id'=>$v['id'],
                'name'=>isset($v['intro'])?$v['intro']:'微信号'.($k+1),
                'url'=>$v['url']
            ];
        }
        $num = count($data);
        if($num<4){
            for($i=0;$i<(4-$num);$i++){
                $data[] = ['id'=>0,'name'=>'微信号'.(count($data)+1),'url'=>''];
            }
        }
        ajaxStatus($data);
    }

    public function getPhonesAction(){
        $id = I('get.id/d');
        $data = [];
        $om = new operatorModel();
        $operator = $om->getInfo(['id'=>$id]);
        if(empty($operator)) ajaxError('运营商不存在');
        if(!empty($operator['phones'])) $data = explode(',',$operator['phones']);
        ajaxStatus($data);
    }
}
