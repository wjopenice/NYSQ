<?php
use Yaf\Application;
class imgModel
{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function upload()
    {
        $key = array_keys($_FILES)[0];
        $url = uploadone($_FILES[$key],'upload');
        $map = [
            'name'=>$_FILES[$key]['name'],
            'url'=>$url,
            'type'=>1,
            'created_time'=>date('Y-m-d H:i:s')
        ];
        $res = $this->db->action($this->db->insertSql('img',$map));
        if(!empty($res)){
            return ['url'=>$url,'id'=>$this->db->getInsertId()];
        }else{
            return [];
        }
    }

    public function rename($data){
         $map = [
             'intro'=>$data['intro'],
             'updated_time'=>date('Y-m-d H:i:s')
         ];
         return $this->db->action($this->db->updateSql('img',$map,'id='.$data['id']));
    }

    public function imgList($data){
        return $this->db->field('*')->table('ny_img')->where(" `type`=1 and is_show=1 and id in({$data['ids']})")->select();
    }
}
