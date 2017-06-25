<?php

require_once '../model/data_access/access.php';
require_once '../model/data_access/lang.php';
require_once 'session.php';
require_once 'file.php';

session_start();
if (!isset($_REQUEST["act"])) {
    exit;
}

switch ($_REQUEST["act"]) {
    case 'register':
        $f = array('email', 'pass', 'avatar', 'username');
        $valid_data = check_validation($f);
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::set_user($_REQUEST['email'], $_REQUEST['pass'], $_REQUEST['avatar'], $_REQUEST['username']);
        if (is_numeric($res)) {
            session::set("user", access::get_user_by_email_pass($_REQUEST['email'], $_REQUEST['pass']));
            send_result(array('Result' => 'profile.html', 'act' => 'location'));
            exit;
        } else {
            send_msg(lang::$last_registered_data, lang::$error);
            exit;
        }
        break;
    case 'login':
        $f = array('email', 'pass');
        $valid_data = check_validation($f);
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        session::set("user", access::get_user_by_email_pass($_REQUEST['email'], $_REQUEST['pass']));
        if (isset($_SESSION['user']) && isset($_SESSION['user'][0]['email'])){
            access::set_user_login($_SESSION['user'][0]['id']);
            send_result(array('Result' => 'index.html', 'act' => 'location'));
            exit;
        } else {
            send_msg(lang::$is_not_login, lang::$error);
            exit;
        }

        break;
    case 'logout':
        try {
            $_SESSION = null;
            session_destroy();
        } catch (Exception $e) {
        }
        send_result(array('act' => 'logout'));
        break;
    case 'edit_profile':
        $f = array('username', 'name', 'email', 'cart_no', 'shaba_no', 'sex', 'address');
        $valid_data = check_validation($f);
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::edit_user($_SESSION['user'][0]['id'], $_REQUEST['username'], $_REQUEST['name'], $_REQUEST['sex'], $_REQUEST['address'], $_REQUEST['email'], $_REQUEST['cart_no'], $_REQUEST['shaba_no']);
        session::set("user", access::get_user_by_email_pass($_SESSION['user'][0]['email'], $_SESSION['user'][0]['password']));
        if ($res == 1) {
            send_result(array('Result' => 'Home.html', 'act' => 'location'));
            exit;
        } else {
            send_msg(lang::$is_not_set_profile, lang::$error);
            exit;
        }
        break;
    case 'get_user':
        if (!isset($_SESSION['user'][0]['email'])) {
            send_result(array('Result' => 'login.html', 'act' => 'location'));
            exit;
        }
        $_SESSION['user'][0]['act'] = 'get_user';
        send_result($_SESSION['user'][0]);
        break;
    case 'get_user_by_username' :
        $res = access::get_user_by_username_or_email($_REQUEST['user']);
        $res['act']='get_user_by_username';
        send_result($res);
        break;
    case 'get_all_file':
        $data = access::get_all_file();
        if($data){
            send_result($data);
        }else{
            send_msg(lang::$empty, lang::$empty);
        }
        break;
    case 'get_all_menus':
            send_result(access::get_all_menu());
        break;
    case 'get_all_menus_join_page':
        send_result(access::get_all_menus_join_page());
        break;
    case 'get_all_roles':
        send_result(access::get_all_role());
        break;
    case 'get_all_maps':
        send_result(access::get_all_map());
        break;
    case 'get_all_pages':
        send_result(access::get_all_page());
        break;
    case 'get_all_articles':
        send_result(access::get_all_articles());
        break;
    case 'get_all_articles_join_page':
        send_result(access::get_all_articles_join_page());
        break;
    case 'get_all_userroles':
        send_result(access::get_all_user_role());
        break;
    case 'get_all_slider':
        send_result(access::get_all_slider());
        break;
    case 'get_all_slider_join_page':
        send_result(access::get_all_slider_join_page());
        break;
    case 'get_all_imagebox':
        send_result(access::get_all_imagebox());
        break;
    case 'get_all_imagebox_join_page':
        send_result(access::get_all_imagebox_join_page());
        break;
    case 'get_menu_by_id':
        $valid_data = check_validation(array("id"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_menu_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'get_role_by_id':
        $valid_data = check_validation(array("id"));
        print_r($valid_data);
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_role_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'get_map_by_id':
        $valid_data = check_validation(array("id"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_map_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'get_page_by_id':
        $valid_data = check_validation(array("id"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_page_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'get_article_by_id':
        $valid_data = check_validation(array("id"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_articles_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'get_userrole_by_userid':
        $valid_data = check_validation(array("userid"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_user_role_by_user_id($_REQUEST['userid']);
        send_result($res);
        break;
    case 'get_slider_by_id':
        $valid_data = check_validation(array("id"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_slider_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'get_imagebox_by_id':
        $valid_data = check_validation(array("id"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_imagebox_by_id($_REQUEST['id']);
        send_result($res);
        break;
    case 'set_page':
        if(checkLogin()){
            $arr = array("title");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data,lang::$error);
                exit;
            }
            $res = access::set_page($_REQUEST['title'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_menu':
        if(checkLogin()){
            $arr = array("name", "parentid", "pageid", "image");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            $res = access::set_menu($_REQUEST['name'], $_REQUEST['parentid'], $_REQUEST['pageid'], $_REQUEST['image'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_article':
        if(checkLogin()){
            $arr = array("pageid", "title", "content", "metadata");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            $res = access::set_articles($_REQUEST['pageid'], $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['metadata'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_role':
        if(checkLogin()){
            $arr = array("role");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            $res = access::set_role($_REQUEST['role'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_userrole':
        if(checkLogin()){
            $arr = array("userid", "roleid");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            $res = access::set_user_role($_REQUEST['userid'], $_REQUEST['roleid'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_map':
        if(checkLogin()){
            $arr = array("lat", "long", "icon");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            $res = access::set_map($_REQUEST['lat'], $_REQUEST['long'], $_REQUEST['icon']);
            print_r($res);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_slider':
        if(checkLogin()){
            $arr = array("title", "url", "pageid", "content");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data,lang::$error);
                exit;
            }
            $res = access::set_slider($_REQUEST['title'], $_REQUEST['url'], $_REQUEST['pageid'], $_REQUEST['content'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_imagebox':
        if(checkLogin()){
            $arr = array("content", "url", "pageid");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data,lang::$error);
                exit;
            }
            $res = access::set_imagebox($_REQUEST['content'], $_REQUEST['url'], $_REQUEST['pageid'], $_SESSION['user'][0]['id']);
            if(is_numeric($res)){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'set_file':
        if(checkLogin()){
            if(access::set_file($_SESSION['user'][0]['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }
        }
        break;
    case 'delete_file':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_file($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_page':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_page($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_menu':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_menu($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_article':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_articles($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_map':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_map($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_role':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_role($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_userrole':
        if(checkLogin()){
            $valid_data = check_validation(array("userid"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_user_role($_REQUEST['userid'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_slider':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_slider($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'delete_imagebox':
        if(checkLogin()){
            $valid_data = check_validation(array("id"));
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if (access::delete_imagebox($_REQUEST['id'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_page':
        if(checkLogin()){
            $arr = array("id", "title");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_page($_REQUEST['id'], $_REQUEST['title'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_menu':
        if(checkLogin()){
            $arr = array("id", "name", "parentid", "pageid", "url", "image");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_menu($_REQUEST['id'], $_REQUEST['name'], $_REQUEST['parentid'], $_REQUEST['pageid'], $_REQUEST['url'], $_REQUEST['image'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_article':
        if(checkLogin()){
            $arr = array("id", "pageid", "title", "content", "metadata");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_articles($_REQUEST['id'], $_REQUEST['pageid'], $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['metadata'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_role':
        if(checkLogin()){
            $arr = array("id", "role");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_role($_REQUEST['id'], $_REQUEST['role'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_userrole':
        if(checkLogin()){
            $arr = array("userid", "roleid");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_user_role($_REQUEST['userid'], $_REQUEST['roleid'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_map':
        if(checkLogin()){
            $arr = array("id", "lat", "long", "icon");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_map($_REQUEST['id'], $_REQUEST['lat'], $_REQUEST['long'], $_REQUEST['icon'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_slider':
        if(checkLogin()){
            $arr = array("id", "title", "url", "pageid", "content");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            $res = access::update_slider($_REQUEST['id'], $_REQUEST['title'], $_REQUEST['url'],$_REQUEST['pageid'],$_REQUEST['content']);
            if($res){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'update_imagebox':
        if(checkLogin()){
            $arr = array("id", "content", "url", "pageid");
            $valid_data = check_validation($arr);
            if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
                send_msg(lang::$invalid_data, lang::$error);
                exit;
            }
            if(access::update_imagebox($_REQUEST['id'], $_REQUEST['content'], $_REQUEST['url'],$_REQUEST['pageid'])){
                send_msg(lang::$success, lang::$message, "success");
            }else{
                send_msg(lang::$failed, lang::$error);
            }
        }
        break;
    case 'get_articles_page':
        $valid_data = check_validation(array("pageid"));
        if (!isset($valid_data['is_valid']) || $valid_data['is_valid'] == false) {
            send_msg(lang::$invalid_data, lang::$error);
            exit;
        }
        $res = access::get_articles_page($_REQUEST['pageid']);
        send_result($res);
        break;
    case 'get_all_menus_join_menus':
        $res = access::get_all_menus_join_menus();
        send_result($res);
        break;

    default:
    send_result(false);
}

function check_validation($field){
    $result['is_valid'] = true;
    for ($i = 0; count($field) > $i; $i++){
        if (isset($_REQUEST[$field[$i]])) {
            $result[$field[$i]] = $_REQUEST[$field[$i]];
        } else {
            $result[$field[$i]] = false;
            $result['is_valid'] = false;
        }
    }
    return $result;
}

function send_msg($msg, $title,$type = "error", $btn = ""){
    send_result(array('msg' => $msg, 'title' => $title, 'type'=>$type , 'btn'=>$btn ,  'act' => 'message' ));
    exit;
}

function send_result($res){
    echo json_encode($res);
}

function checkLogin(){
    if (!isset($_SESSION['user']) && !isset($_SESSION['user'][0]['email'])){
        send_result(array('Result' => 'login.html', 'act' => 'location'));
        exit;
    }
    else{
        return true;
    }
}