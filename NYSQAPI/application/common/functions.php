<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 13:21
 */
function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger','huawei');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}



function isweixin() {
    $clientkeywords = ['micromessenger'];
    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
        return true;
    }else{
        return false;
    }
}

function isapple() {
    $clientkeywords = ['iphone','ipod'];
    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
        return true;
    }else{
        return false;
    }
}

function alertText($data,$url) {
    echo "<script>
    var divNode = document.createElement('div');
    divNode.setAttribute('id','msg');
    divNode.style.position = 'fixed';
    divNode.style.top = '50%';
    divNode.style.width = '400px';
    divNode.style.left = '50%';
    divNode.style.marginLeft = '-220px';
    divNode.style.height = '30px';
    divNode.style.lineHeight = '30px';
    divNode.style.marginTop = '-35px';
    var pNode = document.createElement('p');
    pNode.style.background = 'rgba(0,0,0,0.6)';
    pNode.style.width = '300px';
    pNode.style.color = '#fff';
    pNode.style.textAlign = 'center';
    pNode.style.padding = '20px';
    pNode.style.margin = '0 auto';
    pNode.style.fontSize = '16px';
    pNode.style.borderRadius = '4px';
    pNode.innerText = '".$data."';
    divNode.appendChild(pNode);
    var htmlNode = document.documentElement;
    htmlNode.style.background = 'rgba(0,0,0,0)';
    htmlNode.appendChild(divNode);
    var t = setTimeout(next,2000);
    function next(){
        htmlNode.removeChild(divNode);
        window.location.href='".$url."';
    }
    </script>";
}
function success($msg,$url){
    echo "<script>alert('".$msg."');window.location.href='".$url."';</script>";
}
function error($msg){
    echo "<script>alert('".$msg."');window.history.back();</script>";
}
function statusUrl($bool,string $success_msg, string $success_url,string $error_msg){
    if($bool){
        success($success_msg,$success_url);
    }else{
        error($error_msg);
    }
}
function server($data = null){
    if(is_null($data)){
        return $_SERVER;
    }else{
        $key = strtoupper($data);
        return $_SERVER[$key];
    }
}
function request($data = null){
    if(is_null($data)){
        return $_REQUEST;
    }else{
        return $_REQUEST[$data];
    }
}
function post($data = null){
    if(is_null($data)){
        return $_POST;
    }else{
        return $_POST[$data];
    }
}
function get($data = null){
    if(is_null($data)){
        return $_GET;
    }else{
        return $_GET[$data];
    }
}
function files($data = null){
    if(is_null($data)){
        return $_FILES;
    }else{
        return $_FILES[$data];
    }
}

function load_view($filename=null){
    include_once APP_PATH."/application/views/{$filename}.phtml";
}

function p($data){
    if(is_bool($data) || is_null($data)){
        var_dump($data);
    }

    if(is_array($data) || is_object($data) || is_resource($data)){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    if(is_int($data) || is_string($data) || is_float($data)){
        echo $data;
    }

    exit;
}

function dump($data){
    switch (true){
        case is_string($data) || is_int($data) || is_float($data): echo $data ; break; exit;
        case is_array($data) || is_object($data) : echo "<pre>";print_r($data);echo "</pre>"; break;exit;
        case is_bool($data) || is_null($data) : var_dump($data) ; break;exit;
        default: var_dump($data) ;break;exit;
    }
    exit;
}

//写一个数据类型的检测
function dataType($data){
    if(is_string($data)){
        echo "这是字符串";
    }else if(is_int($data)){
        echo "这是整型";
    }else if(is_object($data)){
        echo "这是对象";
    }else if(is_float($data)){
        echo "这是浮点类型";
    }else if(is_bool($data)){
        echo "这是布尔类型";
    }else if(is_null($data)){
        echo "这是NULL";
    }else if(is_array($data)){
        echo "这是数组";
    }else{
        echo "这是资源类型";
    }
    exit;
}

//删除文件
function file_delete($filename=null,$mktime=null){
    if(file_exists($filename)){
        $t1 = fileatime($filename);//获取上一次访问时间
        $t2 = time(); //获取本次访问时间
        $t3 = $t2-$t1;//时间差
        $t4 = $mktime;// 过期时间秒
        if($t3 >= $t4){//过期
            unlink($filename); //删除文件
        }
    }else{
        die($filename." not file code：404");
    }
}

//强化readfile函数安全
function Exreadfile($fileName = null,$tags=true){
    if($tags){
        ob_start();//打开输出缓冲
        readfile($fileName);  //写数据到输出缓冲
        $strData = ob_get_flush();//提前输出缓冲数据和关闭
        ob_clean();//清空输出缓冲里面的内容
        return htmlspecialchars($strData);
    }else{
        ob_start();//打开输出缓冲
        readfile($fileName);  //写数据到输出缓冲
        $strData = ob_get_flush();//提前输出缓冲数据和关闭
        ob_clean();//清空输出缓冲里面的内容
        return $strData;
    }
}

//点击率
function file_addclick($fileName = null){
    $L = filesize($fileName)+1;
    $fileRes1 = fopen($fileName,"r");
    $str = fread($fileRes1,$L);
    $str+=1;
    $fileRes2 = fopen($fileName,"w+");
    fwrite($fileRes2,$str);
    rewind($fileRes2);
    return fread($fileRes2,$L);
}

//PHP生成日历
function datetime(){
    $y = isset($_GET['y'])?$_GET['y']:date("Y"); //当前年
    $m = isset($_GET['m'])?$_GET['m']:date("m"); //当前月
    $d = isset($_GET['d'])?$_GET['d']:date("d"); //当前日
    $days = date("t",mktime(0,0,0,$m,$d,$y));//获取当月的天数
    $statweek = date("w",mktime(0,0,0,$m,1,$y));//获取当月的第一天是星期几
    $str = "";
    $str .="<table border='1' align='center'>";
    $str .="<caption>当前为{$y}年{$m}月</caption>";
    $str .="<tr><th>星期天</th><th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th></tr>";
    $str .="<tr>";
    for($i=0;$i<$statweek;$i++){
        $str .="<td>&nbsp;</td>";
    }
    for($j=1;$j<=$days;$j++){
        $i++;
        if($j == $d){
            $str .="<td bgcolor='cyan'>{$j}</td>";
        }else{
            $str .="<td>{$j}</td>";
        }
        if($i % 7 == 0){
            $str .="</tr><tr>";
        }
    }
    while($i % 7 !== 0){
        $str .="<td>&nbsp;</td>";
        $i++;
    }
    $str .="</tr>";
    $str .="</table>";
    return $str;
}

//转静态化
function static_page($url,$descname){
    set_time_limit(0);
    //实现HTML静态化
    $data = base64_encode(file_get_contents($url));
    file_put_contents($descname,$data);  //W+
    $strData = base64_decode(file_get_contents($descname));
    return $strData;
}

//文件下载
function apkdownload($file){
    if(file_exists($file)){
        header("Content-type:application/vnd.android.package-archive");
        $filename = basename($file);
        header("Content-Disposition:attachment;filename = ".$filename);
        header("Accept-ranges:bytes");
        header("Accept-length:".filesize($file));
        readfile($file);
    }else{
        echo "<script>alert('文件不存在')</script>";
    }
}

function StrX_shuffle($str=null){
    $a1 = range("a","z");
    shuffle($a1);
    $a2 = range("a","z");
    shuffle($a2);
    $a3 = range("a","z");
    shuffle($a3);
    $a4 = range("a","z");
    shuffle($a4);
    $a5 = range("a","z");
    shuffle($a5);
    $a6 = range("a","z");
    shuffle($a6);
    $strData = $str.$a1[0].$a2[0].$a3[0].$a4[0].$a5[0].$a6[0];
    return $strData;
}

//随机字符串
function Mer_shuffle($string,$maxlen = 20){
    $int_arr = range(0,9);
    $str_arr = range("a","z");
    $str1 = mb_splitchar($string);
    $new_arr = array_merge($int_arr,$str_arr);
    shuffle($new_arr);
    $strData = $str1.date("YmdHi",time()).implode($new_arr);
    $new_str = substr($strData,0,$maxlen);
    //file_put_contents("./c.html",$new_str);
    return $new_str;
}
//订单生成
function build_order_no(){
    return date('Ymd').substr(implode(array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
//获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
function getfirstchar($s0){
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "H";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return NULL;
}
//获取整条字符串汉字拼音首字母
function mb_splitchar($str){
    $strX = "";
    for($i=0;$i<mb_strlen($str);$i++){
        $strData = mb_substr($str,$i,1);
        if(ord($strData) > 160){
            $strX .= getfirstchar($strData);
        }else{
            $strX .= $strData;
        }
    }
    return $strX;
}

//获取ip
function getIp() {

    $arr_ip_header = array(
        'HTTP_CDN_SRC_IP',
        'HTTP_PROXY_CLIENT_IP',
        'HTTP_WL_PROXY_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    );
    $client_ip = 'unknown';
    foreach ($arr_ip_header as $key)
    {
        if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown')
        {
            $client_ip = $_SERVER[$key];
            break;
        }
    }
    return $client_ip;
}
//获取具体错误信息
function getE($num="") {
    switch($num) {
        case -1:  $error = '用户名长度必须在6-30个字符以内！'; break;
        case -2:  $error = '用户名被禁止注册！'; break;
        case -3:  $error = '用户名被占用！'; break;
        case -4:  $error = '密码长度不合法'; break;
        case -5:  $error = '邮箱格式不正确！'; break;
        case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
        case -7:  $error = '邮箱被禁止注册！'; break;
        case -8:  $error = '邮箱被占用！'; break;
        case -9:  $error = '手机格式不正确！'; break;
        case -10: $error = '手机被禁止注册！'; break;
        case -11: $error = '手机号被占用！'; break;
        case -12: $error = '手机号码必须由11位数字组成';break;
        case -13: $error = '手机号已被其他账号绑定';break;

        case -20: $error = '请填写正确的姓名';break;
        case -21: $error = '用户名必须由字母、数字或下划线组成,以字母开头';break;
        case -22: $error = '用户名必须由6~30位数字、字母或下划线组成';break;
        case -31: $error = '密码错误';break;
        case -32: $error = '用户不存在或被禁用';break;
        case -41: $error = '身份证无效';break;
        default:  $error = '未知错误';
    }
    return $error;
}

//获取CURD请求类型
function Get_method(){
    $method = $_SERVER['REQUEST_METHOD'];
    return $method;
}
//获取CURD请求数据
function Resp_curl(){
    parse_str(file_get_contents('php://input'), $data);
    $data = array_merge($_GET, $_POST, $data);
    return $data;
}
//建立CURD请求模式
function Rest_curl($url,$type='GET',$data="",$bool=false,array $headers=["content-type: application/x-www-form-urlencoded;charset=UTF-8"]){
    //post 新增  get查询  put修改  delete删除
    $curl= curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL,$url);
    if($bool == true){
        curl_setopt($curl, CURLOPT_HEADER, $bool);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    switch ($type){
        case "GET":break;
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:break;
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,false);
    if(curl_exec($curl) === false){
        return "error code:".curl_getinfo($curl, CURLINFO_HTTP_CODE).',error message:'.curl_error($curl);
    }
    $strData = curl_exec($curl);
    curl_close($curl);
    return $strData;
}

function get_zs_news_type_name($data){
    $str = "";
    switch ($data){
        case 0:$str = "首页N格";break;
        case 1:$str = "首页单格";break;
        case 2:$str = "首页视频";break;
    }
    return $str;
}

function switch_user_level($level){
    $user_level = 1;
    switch(true){
        case $level>=0 && $level<50: $user_level = 1; break;
        case $level>=50 && $level<130: $user_level = 2; break;
        case $level>=130 && $level<290: $user_level = 3; break;
        case $level>=290 && $level<610: $user_level = 4; break;
        case $level>=610 && $level<1250: $user_level = 5; break;
        case $level>=1250 && $level<2530: $user_level = 6; break;
        case $level>=2530 && $level<5090: $user_level = 7; break;
        case $level>=5090 && $level<10210: $user_level = 8; break;
        case $level>=10210 && $level<20450: $user_level = 9; break;
        case $level>=20450 : $user_level = 10; break;
        default:$user_level = 1; break;
    }
    return $user_level;
}

function user_bp($user,$bp,$optime,$bp_type,$jf){
    $db = new dbModel();
    $ptime = date("Y-m-d",$optime); //时间戳转为日期
    $result = $db->field("*")->table("zs_user_bp")->where(" user = {$user} and optime = '{$ptime}' ")->find();
    $user_result = $db->field("*")->table("zs_user")->where(" id = {$user} ")->findobj();
    if(!empty($user_result)){
        if(empty($result)){
            //用户积分变动
            $zs_user['user_exp'] = (int)$user_result->user_exp + $jf;
            $zs_user['user_level'] = switch_user_level($user_result->user_exp);
            $db->action($db->updateSql("user",$zs_user,"id = {$user}"));
            //用户积分日志
            $zs_user_bp['user'] = $user;
            $zs_user_bp['bp'] = $bp;
            $zs_user_bp['optime'] = $ptime;
            $zs_user_bp['bp_type'] = $bp_type;
            $db->action($db->insertSql("user_bp",$zs_user_bp));
        }
    }else{
        echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
    }
}

function user_ios_bp($user,$bp,$optime,$bp_type,$jf){
    $db = new dbModel();
    $ptime = date("Y-m-d",$optime); //时间戳转为日期
    $result = $db->field("*")->table("zs_user_bp")->where(" user = {$user} and optime = '{$ptime}' ")->find();
    $user_result = $db->field("*")->table("zs_ios_user")->where(" id = {$user} ")->findobj();
    if(!empty($user_result)){
        if(empty($result)){
            //用户积分变动
            $zs_user['user_exp'] = (int)$user_result->user_exp + $jf;
            $zs_user['user_level'] = switch_user_level($user_result->user_exp);
            $db->action($db->updateSql("ios_user",$zs_user,"id = {$user}"));
            //用户积分日志
            $zs_user_bp['user'] = $user;
            $zs_user_bp['bp'] = $bp;
            $zs_user_bp['optime'] = $ptime;
            $zs_user_bp['bp_type'] = $bp_type;
            $db->action($db->insertSql("user_bp",$zs_user_bp));
        }
    }else{
        echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
    }
}

function get_zs_type_name($data){
    $db = new dbModel();
    $arrdata = $db->field("*")->table("zs_card_type")->where(" id = {$data} ")->find();
    return $arrdata['title'];
}

function get_zs_map_type_name($data){
    $db = new dbModel();
    $arrdata = $db->field("*")->table("zs_map_type")->where(" map_id = {$data} ")->find();
    return $arrdata['title'];
}

function get_zs_user_name($data){
    $db = new dbModel();
    $arrdata = $db->field("*")->table("zs_user")->where(" id = {$data} ")->find();
    return $arrdata['account'];
}

function get_zs_user_uid($data){
    $db = new dbModel();
    $arrdata = $db->field("*")->table("zs_user")->where(" id = {$data} ")->find();
    return $arrdata['uid'];
}

function get_zs_merc_name($data){
    $str = "";
    switch ($data){
        case "commercial":$str = "经营性";break;
        case "sex":$str = "性别";break;
        case "mobile_num":$str = "手机号";break;
        case "real_name":$str = "姓名";break;
        case "id_card":$str = "身份证号";break;
        case "address":$str = "所在地";break;
        case "info":$str = "说明";break;
        case "idcard":$str = "身份证正面";break;
        case "reidcard":$str = "身份证反面";break;
        case "handidcard":$str = "手持身份证";break;
        case "permit":$str = "营业执照/兽医资格证";break;
        case "field1":$str = "场地照1";break;
        case "field2":$str = "场地照2";break;
        case "field3":$str = "场地照3";break;
        case "field4":$str = "场地照4";break;
    }
    return $str;
}
function get_zs_user_merc_status($id,$data){
    $db = new dbModel();
    $result =  $db->field($data)->table("zs_user_merc")->where(" id = {$id} ")->findobj();
    return $result->$data;
}

function get_zs_user_merc_status_info($data){
    $str = "";
    switch ($data){
        case '0':$str = "<span style='color:#fb2e2e;'>不通过</span>";break;
        case '1':$str = "<span style='color:#286f28;'>通过</span>";break;
        case '2':$str = "<span style='color:#2b8ae0;'>未审核</span>";break;
    }
    echo $str;
}

function str_rep($str){
    $db = new dbModel();
    $arrData = $db->field("word")->table("zs_filter")->select();
    $arr = [];
    foreach ($arrData as $key=>$value){
        $arr[$key] = $value['word'];
    }
    $re = "***";
    return str_replace($arr,$re,$str);
}

//判断时间在今天、昨天、前天、几天前几点
function get_time($targetTime)
{
    // 今天最大时间
    $todayLast   = strtotime(date('Y-m-d 23:59:59'));
    $agoTimeTrue = time() - $targetTime;
    $agoTime     = $todayLast - $targetTime;
    $agoDay      = floor($agoTime / 86400);
    if ($agoTimeTrue < 60) {
        $result = '刚刚';
    } elseif ($agoTimeTrue < 3600) {
        $result = (ceil($agoTimeTrue / 60)) . '分钟前';
    } elseif ($agoTimeTrue < 3600 * 12) {
        $result = (ceil($agoTimeTrue / 3600)) . '小时前';
    } elseif ($agoDay == 0) {
        $result = '今天 ' ;
    } elseif ($agoDay == 1) {
        $result = '昨天 ' ;
    } elseif ($agoDay == 2) {
        $result = '前天 ';
    } elseif ($agoDay > 2 && $agoDay < 16) {
        $result = $agoDay . '天前 ';
    } else {
        $format = date('Y') != date('Y', $targetTime) ? "Y-m-d H:i" : "m-d H:i";
        $result = date($format, $targetTime);
    }
    return $result;
}


if(!function_exists('get_userinfo_data')){
    function get_userinfo_data($uid){
        $db = new dbModel();
        $userdata['follow_num'] = count($db->field("*")->table("zs_attention")->where("uid = {$uid}")->select());
        $userdata['fans_num'] = count($db->field("*")->table("zs_fans")->where("uid = {$uid}")->select());
        $userdata['collection_num'] = count($db->field("*")->table("zs_collection")->where("u_id = {$uid}")->select());
        $price = $db->field("sum(pay_amount) as commonweal_price")->table("zs_weal_order")->where("user_id = {$uid} and pay_status = 1")->find();
        $userdata['commonweal_price'] = ($price['commonweal_price'] == null)?"0.00":$price['commonweal_price'];
        return $userdata;
    }
}




//function isWechat() {
//    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
//    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
//        return true;
//    }
//    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
//    if (isset($_SERVER['HTTP_VIA'])) {
//        // 找不到为flase,否则为true
//        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
//    }
//    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
//    if (isset($_SERVER['HTTP_USER_AGENT'])) {
//        $clientkeywords = array('MicroMessenger');
//        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
//        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
//            return true;
//        }
//    }
//    // 协议法，因为有可能不准确，放到最后判断
//    if (isset ($_SERVER['HTTP_ACCEPT'])) {
//        // 如果只支持wml并且不支持html那一定是移动设备
//        // 如果支持wml和html但是wml在html之前则是移动设备
//        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
//            return true;
//        }
//    }
//    return false;
//}

//function isiphone() {
//    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
//    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
//        return true;
//    }
//    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
//    if (isset($_SERVER['HTTP_VIA'])) {
//        // 找不到为flase,否则为true
//        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
//    }
//    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
//    if (isset($_SERVER['HTTP_USER_AGENT'])) {
//        $clientkeywords = array('iphone','ipod');
//        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
//        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
//            return true;
//        }
//    }
//    // 协议法，因为有可能不准确，放到最后判断
//    if (isset ($_SERVER['HTTP_ACCEPT'])) {
//        // 如果只支持wml并且不支持html那一定是移动设备
//        // 如果支持wml和html但是wml在html之前则是移动设备
//        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
//            return true;
//        }
//    }
//    return false;
//}
/*
if(!function_exists('showPage')){
    function showPage($page,$url){
        $start = ($page->current>2)?($page->current-2):1;
        $end = ($page->current+1)>$page->total_pages?($page->total_pages-1):$page->current+1;
        $strpage = "";
        $strpage .= "<div id='pages'>";
        $strpage .= \Phalcon\Tag::linkTo($url."?page=".$page->first, "首页");
        if($page->current == $page->first){
            $strpage .= \Phalcon\Tag::linkTo([$url.'?page='.$page->before, '<i class="layui-icon"></i>','class'=>'layui-disabled']);
        }else{
            $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$page->before, '<i class="layui-icon"></i>');
        }
        for ($i=$start;$i<=$end;$i++){
            if($i == $page->current){
                $strpage .= \Phalcon\Tag::linkTo([$url.'?page='.$i, $i,'class' => 'active']);
            }else{
                $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$i, $i);
            }
        }
        if($page->current == $page->total_pages){
            $strpage .= \Phalcon\Tag::linkTo([$url.'?page='.$page->next, '<i class="layui-icon"></i>','class'=>'layui-disabled']);
        }else{
            $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$page->next, '<i class="layui-icon"></i>');
        }
        $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$page->last, '末页');
        $strpage .= "共".$page->total_items."条总共".$page->current." / ".$page->total_pages;
        $strpage .= "</div>";
        return $strpage;
    }
}

*/

//数据库备份
//function mysqldump($tableName){
//    $username = Yii::$app->params['user'];//你的MYSQL用户名
//    $password = Yii::$app->params['pass'];;//密码
//    $hostname = Yii::$app->params['host'];;//MYSQL服务器地址
//    $dbname   = Yii::$app->params['dbname'];;//数据库名
//    $port   = Yii::$app->params['port'];;//数据库端口
//    $dumpfname = $tableName . "_" . date("YmdHi").".sql";
//    $path = dirname(dirname(__FILE__))."/data/".$dumpfname;
//    $command = "mysqldump -P{$port} -h{$hostname} -u{$username} -p{$password} {$dbname} {$tableName} > {$path}";
//    system($command,$retval);
//    exit;
//}
//
////数据库备份
//function mysqldumpall($tableName){
//    $username = Yii::$app->params['user'];//你的MYSQL用户名
//    $password = Yii::$app->params['pass'];;//密码
//    $hostname = Yii::$app->params['host'];;//MYSQL服务器地址
//    $dbname   = Yii::$app->params['dbname'];;//数据库名
//    $port   = Yii::$app->params['port'];;//数据库端口
//    $dumpfname =  "localhost_" . date("YmdHi").".sql";
//    $path = dirname(dirname(__FILE__))."/data/".$dumpfname;
//    $command = "mysqldump -P{$port} -h{$hostname} -u{$username} -p{$password} {$dbname} {$tableName} > {$path}";
//    system($command,$retval);
//    $zipfname = "localhost_" . date("YmdHi").".zip";
//    $zippath = dirname(dirname(__FILE__))."/data/".$zipfname;
//    $zip = new \ZipArchive();
//    if($zip->open($zippath,ZIPARCHIVE::CREATE))
//    {
//        $zip->addFile($path,$path);
//        $zip->close();
//    }
//    if (file_exists($zippath)) {
//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename='.basename($zippath));
//        flush();
//        readfile($zippath);
//        exit;
//    }
//}

/**
 * 获取输入参数 支持过滤和默认值
 * 使用方法:
 * <code>
 * I('id',0); 获取id参数 自动判断get或者post
 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
 * I('get.'); 获取$_GET
 * </code>
 * @param string $name 变量的名称 支持指定类型
 * @param mixed $default 不存在的时候默认值
 * @param mixed $filter 参数过滤方法
 * @param mixed $datas 要获取的额外数据源
 * @return mixed
 */
function I($name,$default='',$filter=null,$datas=null) {
    static $_PUT	=	null;
    if(strpos($name,'/')){ // 指定修饰符
        list($name,$type) 	=	explode('/',$name,2);
    }elseif(false){ // 默认强制转换为字符串
        $type   =   's';
    }
    if(strpos($name,'.')) { // 指定参数来源
        list($method,$name) =   explode('.',$name,2);
    }else{ // 默认为自动判断
        $method =   'param';
    }
    switch(strtolower($method)) {
        case 'get'     :
            $input =& $_GET;
            break;
        case 'post'    :
            $input =& $_POST;
            break;
        case 'put'     :
            if(is_null($_PUT)){
                parse_str(file_get_contents('php://input'), $_PUT);
            }
            $input 	=	$_PUT;
            break;
        case 'param'   :
            switch($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $input  =  $_POST;
                    break;
                case 'PUT':
                    if(is_null($_PUT)){
                        parse_str(file_get_contents('php://input'), $_PUT);
                    }
                    $input 	=	$_PUT;
                    break;
                default:
                    $input  =  $_GET;
            }
            break;
        case 'path'    :
            $input  =   array();
            if(!empty($_SERVER['PATH_INFO'])){
                $depr   =   '/';
                $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
            }
            break;
        case 'request' :
            $input =& $_REQUEST;
            break;
        case 'session' :
            $input =& $_SESSION;
            break;
        case 'cookie'  :
            $input =& $_COOKIE;
            break;
        case 'server'  :
            $input =& $_SERVER;
            break;
        case 'globals' :
            $input =& $GLOBALS;
            break;
        case 'data'    :
            $input =& $datas;
            break;
        default:
            return null;
    }
    if(''==$name) { // 获取全部变量
        $data       =   $input;
        $filters    =   isset($filter)?$filter:'htmlspecialchars';
        if($filters) {
            if(is_string($filters)){
                $filters    =   explode(',',$filters);
            }
            foreach($filters as $filter){
                $data   =   array_map_recursive($filter,$data); // 参数过滤
            }
        }
    }elseif(isset($input[$name])) { // 取值操作
        $data       =   $input[$name];
        $filters    =   isset($filter)?$filter:'htmlspecialchars';
        if($filters) {
            if(is_string($filters)){
                if(0 === strpos($filters,'/')){
                    if(1 !== preg_match($filters,(string)$data)){
                        // 支持正则验证
                        return   isset($default) ? $default : null;
                    }
                }else{
                    $filters    =   explode(',',$filters);
                }
            }elseif(is_int($filters)){
                $filters    =   array($filters);
            }

            if(is_array($filters)){
                foreach($filters as $filter){
                    if(function_exists($filter)) {
                        $data   =   is_array($data) ? array_map_recursive($filter,$data) : $filter($data); // 参数过滤
                    }else{
                        $data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
                        if(false === $data) {
                            return   isset($default) ? $default : null;
                        }
                    }
                }
            }
        }
        if(!empty($type)){
            switch(strtolower($type)){
                case 'a':	// 数组
                    $data 	=	(array)$data;
                    break;
                case 'd':	// 数字
                    $data 	=	(int)$data;
                    break;
                case 'f':	// 浮点
                    $data 	=	(float)$data;
                    break;
                case 'b':	// 布尔
                    $data 	=	(boolean)$data;
                    break;
                case 's':   // 字符串
                default:
                    $data   =   (string)$data;
            }
        }
    }else{ // 变量默认值
        $data       =    isset($default)?$default:null;
    }
    is_array($data) && array_walk_recursive($data,'think_filter');
    return $data;
}

function array_map_recursive($filter, $data) {
    $result = array();
    foreach ($data as $key => $val) {
        $result[$key] = is_array($val)
            ? array_map_recursive($filter, $val)
            : call_user_func($filter, $val);
    }
    return $result;
}

function think_filter(&$value){
    // TODO 其他安全过滤
    // 过滤查询特殊字符
    if(preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i',$value)){
        $value .= ' ';
    }
}

//$data = Array
//(
//    [$key] => Array
//    (
//        [0] => {"url":"http://192.168.0.107/public/upload/1578275300/a.png","uid":1578275325078,"status":"success"}
//    )
//
//    [$key] => Array
//    (
//        [0] => {"url":"http://192.168.0.107/public/upload/1578275304/a.png","uid":1578275325083,"status":"success"}
//    )
//
//    [$key] => string
//
//    [$key] => ArrayArray
//    (
//        [0] => Array
//        (
//            [url] => http://192.168.0.107/public/upload/1578312428/08113204_1036.jpg
//            [uid] => 1578374997782
//            [status] => success
//        )
//
//     )
//)
function get_url($data,$key){
    if(isset($data[$key])){
       if(is_array($data[$key])){
           $strurl = '';
           foreach ($data[$key] as $k=>$v){
               if(is_array($v)){
                   $strurl .= strstr($v['url'],"/public").",";
               }else{
                   $resdata = json_decode($v);
                   $strurl .= strstr($resdata->url,"/public").",";
               }
           }
           return substr($strurl,0,-1);
       }else{
           return $data[$key];
       }
    }else{
       return NULL;
    }
}

function _httpPost($url="" ,$requestData=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $info = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Errno'.curl_error($ch);
    }
    curl_close($ch);

    return $info;
}

function _request($curl, $https=true, $method='get', $data=null){
    $ch = curl_init();//初始化
    curl_setopt($ch, CURLOPT_URL, $curl);//设置访问的URL
    curl_setopt($ch, CURLOPT_HEADER, false);//设置不需要头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//只获取页面内容，但不输出
    if($https){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不做服务器认证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不做客户端认证
    }
    if($method == 'post'){
        curl_setopt($ch, CURLOPT_POST, true);//设置请求是POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置POST请求的数据
    }
    $str = curl_exec($ch);//执行访问，返回结果
    curl_close($ch);//关闭curl，释放资源
    return $str;
}

function getWechatUserInfo($access_token,$openid){
    $url= 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    $data = http_request($url);  //返回json数据
    $data = escapeEmoji($data); //将emoji的unicode留下，其他不动
    $res = json_decode($data,true);
    return $res;
}

/*获取access_token   返回数组*/
function get_access_tokenApi($appid=NULL,$appsecret=NULL){
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
    $res = http_request($url);
    $data=json_decode($res,true);
    return $data;
}

/**
 *检查access_token是否有效
 * 参数 access_token   数组信息
 */
function check_access_token($access_token=NULL){
    //access_token 过期了
    if(time()-$access_token['time']+240 >$access_token['expires_in']){
        return false;
    }else{
        return true;
    }
}

function escapeEmoji ($strText,$bool = false) {
    $preg =  '/\\\ud([8-9a-f][0-9a-z]{2})/i';
    if ($bool == true) {
        $boolPregRes = (preg_match($preg,json_encode($strText,true)));
        return $boolPregRes;
    } else {
        $strPregRes = (preg_replace($preg,'',json_encode($strText,true)));
        $strRet = json_decode($strPregRes,true);
        return $strRet;
    }
}

function hmac256($data){
    $key = \Yaf\Application::app()->getConfig()->config['encrypt_app_key'];
    return hash_hmac("sha256",$data,$key);
}

function ajaxStatus($res){
    $result['code'] = 20000;
    $result['msg'] = 'success';
    $result['data'] = $res;
    echo json_encode($result,320);
    exit;
}

function ajaxError($res,$code=500){
    $result['code'] = $code;
    $result['msg'] = 'error';
    $result['data'] = $res;
    echo json_encode($result,320);
    exit;
}

/**
 * http_request curl
 * @param string $url 地址
 * @data array|json 参数
 * @return $output json
 */
function http_request ($url , $data = NULL) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    // 检查是否有错误发生
    if(curl_errno($ch)){
        echo 'CURL ERROR CODE: '. curl_errno($ch) . ' , reason : ' . curl_error($ch);
    }
    curl_close($ch);
    //$jsoninfo = json_decode($output , true);
    return $output;
}

//curl_post调用微信接口
function curl_post($post_data,$url){
    $post_data = urldecode (json_encode($post_data,JSON_UNESCAPED_UNICODE) );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    $output = json_decode($output,true);
    return $output;
}

function uploadone($file,$path){
    $time = time();
    $dir = APP_PATH."/public/".$path."/".$time;
    if(!file_exists($dir)){
        mkdir($dir,0777,true);
    }
    $pathicon = $dir."/".$file['name'];
    move_uploaded_file( $file['tmp_name'],$pathicon);

    $fileArr = "/public/".$path."/".$time."/".$file['name'];
    return $fileArr;
}
