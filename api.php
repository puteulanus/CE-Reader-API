<?php

require_once('class.CEPicture.php');// 导入CE操作类
require_once('config.inc.php');// 导入API配置文件

$ce = new CEpiture; // 实例化CEPicture对象
$ce->api_url = API_URL; // 设定API地址
switch ($_GET['command']){
case 'list':
    if (!$_GET['w']){
        $width = 300;
    }else{
        $width = $_GET['w'];
    }
    $height = (int)$width * 1.4;
    $ce->CeLoadPage($_GET['num']); // 载入指定页面
    $list = $ce->GetMangaList();
    $cover = $ce->GetMangaCover($width,$height);
    $tmp_array = array();
    foreach($list as $id => $value){
        $tmp_array = array_merge($tmp_array,array($id));
    }
    $cover = array_combine($tmp_array,$cover);
    echo json_encode(array('list' => $list,'cover' => $cover));
    break;
    
case 'info':
    $info = GetInfoFromCache($_GET['num']);
    // 检查CDN是否正常
    if (file_get_contents(CDN_URL."?check=on") == 'OK'){
        $pic_server = CDN_URL;
    }else{// CDN故障时切到本体输出
        $pic_server = SELF_URL;
    }
    foreach($info['thumbnail'] as &$key){
        $key = $pic_server.'?pic='.$key;
        unset($key);
    }
    foreach($info['manga'] as &$key){
        $key = $pic_server.'?pic='.$key;
        unset($key);
    }
    echo json_encode($info);
    break;
    
case 'pic':
    $pic = $_GET['pic'];
	preg_match('/\/([^\.\/]+\.\w{3})$/', $pic,$pic_file_name);
    $pic_file_name = $pic_file_name[1];
    preg_match('/\.(\w{3})$/', $pic_file_name,$file_type);
    $file_type = $file_type[1];
    Header("Content-type: image/".$file_type);
    echo file_get_contents($pic);
    break;
    
default:
  echo "CE Reader API 0.1";
}

function GetInfoFromCache($id){
    $db_link = new mysqli(db_host,db_user,db_passwd,db_name,db_port);
    $stmt_read = $db_link -> prepare("SELECT information FROM ".db_prefix."cache WHERE id=?");
    $stmt_write = $db_link -> prepare("INSERT INTO ".db_prefix."cache (id,information) VALUES (?,?)");
    $stmt_read -> bind_param("s",$id);
    $stmt_read -> execute();
    $stmt_read -> bind_result($json_result);
    $stmt_read -> fetch();
    $stmt_read -> close();
    $array_result = json_decode($json_result,true);
    if ($array_result){
        $stmt_write -> close();
        $db_link -> close();
        return $array_result;
    }
    global $ce;
    $info = $ce->GetMangaInfo($id);
    $stmt_write -> bind_param("ss",$id,json_encode($info));
    $stmt_write -> execute();
    $stmt_write -> close();
    $db_link -> close();
    return $info;
}
