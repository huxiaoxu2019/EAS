<?php 
use Think\Log;
use Common\Library\Int\Model\AdminModel;
/**
 * 获取配置信息.
 *
 * @param string $filename Conf文件夹中的配置文件名称
 *
 * @return array
 */
function get_config($filename = 'Web.ini', $isArr = false, $process_sections = true) {
    $arr        = explode("/", $filename);
    $filename   = "";
    foreach ($arr as $k => $v) {
        $filename .= ucwords(strtolower($v)) . "/";
    }
    $filename = rtrim($filename, "/");
    $filename = dirname(dirname(dirname((dirname(dirname(__FILE__)))))) . "/Conf/{$filename}";
    if(!file_exists($filename)) {
        return array();
    }
    if($isArr === false) {
        $result = parse_ini_file($filename, $process_sections);
    } else {
        $result = require $filename;
    }
    return ($result == false || !is_array($result)) ? array() : $result;
}

/**
 * 获取语言包.
 * 
 * @param string $filename
 * @param string $lang
 * @param bool   $isArr 
 * @param string $process_sections
 * 
 * @return array
 */
function get_lang($filename = 'Web/Common.php', $lang = 'ZH-CN', $isArr = false, $process_sections = true) {
    $arr        = explode("/", $filename);
    $filename   = "";
    foreach ($arr as $k => $v) {
        $filename .= ucwords(strtolower($v)) . "/";
    }
    $filename = rtrim($filename, "/");
    $filename = dirname(dirname(dirname((dirname(dirname(__FILE__)))))) . "/I18N/{$lang}/{$filename}";
    if(!file_exists($filename)) {
        return array();
    }
    if($isArr === false) {
        $result = parse_ini_file($filename, $process_sections);
    } else {
        $result = require $filename;
    }
    return ($result == false || !is_array($result)) ? array() : $result;
}

/**
 * 判断是否登陆.
 *
 * @author genialx
 * @param string $type 用户类型
 * @return boolean
 */
function is_log($type = AdminModel::ADMIN_SESSION_ID) {
    if(session("?{$type}")) return true;
    return false;
}

/**
 * 获取当前登录的管理员ID.
 *
 * @return mixed|boolean
 */
function get_admin_id($type = AdminModel::ADMIN_SESSION_ID) {
    if(session("?{$type}")) return session("{$type}");
    return false;
}

/**
 * 设置登陆标记（session）.
 *
 * @author genialx
 * @param string $type
 * @param string $value
 * @return boolean
 */
function set_log($type = null, $value = null) {
    if(!isset($value)) return false;
    if(session("?{$type}")) session($type, null);
    Log::record("[SESSION] index: {$type} value: {$value}", Log::INFO);
    session($type, $value);
    return true;
}

/**
 * 登出.
 * @author genialx
 * @param string $type
 * @return boolean
 */
function log_out($type = null) {
    if(session("?{$type}")) return session($type, null);
    return false;
}

/**
 * Login action.
 *
 * @param string $username
 * @param string $userpass
 * @param string $type
 * @return boolean
 */
function login($username, $userpass, $type = AdminModel::ADMIN_SESSION_ID) {
    $data = D('admin')->field('id')->where(array('account'=>$username, 'password'=>$userpass))->find();
    if(count($data) > 0) return set_log($type, $data['id']);
    return false;
}

/**
 * Get now time format 'Y-m-d H:i:s'
 *
 */
function get_now_time() {
    return date("Y-m-d H:i:s", time());
}

/**
 * 打印函数.
 *
 */
function p($v) {
    echo "<pre>" . print_r($v,true) . "</pre>";
}