<?php

use Yaf\Application;
class operatorModel
{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function addBackups($data){
        $id = isset($data['id'])?(int)$data['id']:0;
        $p_name = isset($data['name'])?$data['name']:'';
        $old_name = isset($data['old_name'])?$data['old_name']:'';
        $company_name = isset($data['company_name'])?$data['company_name']:'';
        $tag_ids = isset($data['tag_ids'])?$data['tag_ids']:'';
        $banner_ids = isset($data['banner_ids'])?$data['banner_ids']:'';
        $qrcode_img_ids = isset($data['qrcode_img_ids'])?$data['qrcode_img_ids']:'';
        $address = isset($data['address'])?$data['address']:'';
        $longitude = isset($data['longitude'])?(int)$data['longitude']:0;
        $latitude = isset($data['latitude'])?(int)$data['latitude']:0;
        $phone = isset($data['phone'])?$data['phone']:'';
        $video_id = isset($data['video_id'])?(int)$data['video_id']:0;
        $video_url = isset($data['video_url'])?$data['video_url']:'';
        $sql = "call func_operator_log('$p_name','$company_name','$tag_ids','$banner_ids','$qrcode_img_ids','$address',$longitude,$latitude,'$phone',$video_id,'$video_url',$id,'$old_name')";
        $res = $this->db->action($sql);
        return $res;
    }

    public function getInfo($data){
        $res = $this->db->field('*')
            ->table('ny_operator')
            ->where('id='.$data['id'])
            ->find();
        return $res;
    }
}
