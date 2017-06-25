<?php

require_once '../model/database/data.php';

class access{

    public static function set_page($title, $createBy){
        return data::insertinto("`pages`", "`id`, `title`, `enable`, `isdelete`, `createby`, `creationdate`", "NULL, '$title', '1', '0', '$createBy', CURRENT_TIMESTAMP");
    }

    public static function get_page_by_id($id){
        $data = data::selects("`pages`", "`id` = $id and `isdelete` = '0'");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_page_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`pages`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`pages`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_page(){
        $data = data::selects("`pages`", "`isdelete` = '0'");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_menus_join_page(){
        return data::selects_join("`pages`", "`menus`", "`menus`.*, `pages`.title as page_title", false, "`pages`.id = `menus`.pageid", false, false, false, true);
    }

    public static function update_page($id, $title){
        return data::update("`pages`", "`title` = '$title'", "`id` = $id");
    }

    public static function delete_page($id){
        return data::update("`pages`", "`isdelete` = '1'","`id` = $id");
    }

    public static function set_menu($name, $parentId, $pageId, $image, $createBy){
        $res = file::upload($_FILES['image']);
        if(!$res['error']){
            return data::insertinto("`menus`", "`id`, `name`, `parentid`, `pageid`, `url`, `enable`, `isdelete`, `image`, `createby`, `creationdate`", "NULL, '$name', '$parentId', '$pageId', '".$res['url']."', '1', '0', '$image', '$createBy', CURRENT_TIMESTAMP");
        }
        return false;
    }

    public static function get_menu_by_id($id){
        $data = data::selects("`menus`", "`id` = $id and `isdelete` = '0'");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_menu_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`menus`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`menus`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_menus_join_menus(){
        $data = data::selects_join("`menus`", "`menus` as b", "menus.id, menus.name, menus.parentid, b.name as sub_menu, menus.url, menus.enable, menus.image, menus.isdelete, menus.createby, menus.creationdate", "INNER", "menus.id = b.parentid", "menus.isdelete = 0", false, false, true);
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_menu(){
        $data = data::selects("`menus`", "`isdelete` = '0'");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function update_menu($id, $name, $parentId, $pageId, $url, $image){
        return data::update("`menus`", "`name` = '$name', `parentid` = '$parentId', `pageid` = '$pageId', `url` = '$url', `image` = '$image'", "`id` = $id");
    }

    public static function delete_menu($id){
        $data = data::selects_col("`menus`", "`image`", "`id` = $id");
        file::delete($data[0]['image']);
        return data::update("`menus`", "`isdelete` = '1'","`id` = $id");
    }

    public static function set_articles($pageId, $title, $content, $metaData, $createBy){
        return data::insertinto("`articles`", "`id`, `pageid`, `priority`, `title`, `content`, `metadata`, `createby`, `creationdate`", "NULL, '$pageId', '0', '$title', '$content', '$metaData', '$createBy', CURRENT_TIMESTAMP");
    }

    public static function get_articles_by_id($id){
        $data = data::selects("`articles`", "`id` = $id");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_articles_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`articles`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`articles`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_articles_page($pageId){
        $data = data::selects("`articles`", "`pageid` = '$pageId'");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_articles(){
        $data = data::selects("`articles`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_articles_join_page(){
        return data::selects_join("`pages`", "`articles`", "`articles`.*, `pages`.title as page_title", false, "`pages`.id = `articles`.pageid", false, false, false, true);
    }

    public static function update_articles($id, $pageId, $title, $content, $metaData){
        return data::update("`articles`", "`pageid` = '$pageId', `title` = '$title', `content` = '$content', `metadata` = '$metaData'", "`id` = $id");
    }

    public static function delete_articles($id){
        return data::delete("`articles`", "`id` = $id");
    }

    public static function set_role($role, $createBy){
        return data::insertinto("`roles`", "`id`, `role`, `createby`, `creationdate`", "NULL, '$role', '$createBy', CURRENT_TIMESTAMP");
    }

    public static function get_role_by_id($id){
        $data = data::selects("`roles`", "`id` = $id");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_role_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`roles`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`roles`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_role(){
        $data = data::selects("`roles`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function update_role($id, $role){
        return data::update("`roles`", "`role` = '$role'", "`id` = $id");
    }

    public static function delete_role($id){
        return data::delete("`roles`", "`id` = $id");
    }

    public static function set_user_role($userId, $roleId, $createBy){
        return data::insertinto("`userRoles`", "`userid`, `roleid`, `createby`, `creationdate`", "'$userId', '$roleId', '$createBy', CURRENT_TIMESTAMP");
    }

    public static function get_user_role_by_user_id($userId){
        $data = data::selects("`userRoles`", "`userid` = $userId");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_userrole_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`userRoles`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`userRoles`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_user_role(){
        $data = data::selects("`userRoles`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function update_user_role($userId, $roleId){
        return data::update("`userRoles`", "`userid` = '$userId', `roleid` = '$roleId'", "`userid` = $userId");
    }

    public static function delete_user_role($userId){
        return data::delete("`userRoles`", "`userid` = $userId");
    }

    public static function set_map($lat, $lng, $icon){
        return data::insertinto("`maps`", "`id`, `lat`, `long`, `icon`", "NULL, '$lat', '$lng', '$icon'");
    }

    public static function get_map_by_id($id){
        $data = data::selects("`maps`", "`id` = $id");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_map_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`maps`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`maps`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_map(){
        $data = data::selects("`maps`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function update_map($id, $lat, $lng, $icon){
        return data::update("`maps`", "`lat` = '$lat', `long` = '$lng', `icon` = '$icon'", "`id` = $id");
    }

    public static function delete_map($id){
        return data::delete("`maps`", "`id` = $id");
    }

    public static function get_user_by_email_pass($email , $pass){
        $data = data::selects('`users`', "`email` = '$email' AND  `password` ='$pass'" );
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function set_slider($title, $url, $pageid, $content, $createby){
        $res = file::upload($_FILES['image']);
        if(!$res['error']){
            return data::insertinto("`slider`", "`id`, `title`, `image`, `url`, `pageid`, `content`, `createby`, `creationdate`","NULL, '$title', '".$res['url']."', '$url', '$pageid', '$content', '$createby', CURRENT_TIMESTAMP");
        }
        else{
            send_msg($res['message'], lang::$error);
        }
    }

    public static function get_slider_by_id($id){
        $data = data::selects("`slider`", "`id` = $id");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_slider_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`slider`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`slider`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_slider(){
        $data = data::selects("`slider`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_slider_join_page(){
        return data::selects_join("`pages`", "`slider`", "`slider`.*, `pages`.title as page_title", false, "`pages`.id = `slider`.pageid", false, false, false, true);
    }

    public static function update_slider($id, $title, $url, $pageid, $content){
        if(!empty($_FILES['image'])){
            $data = data::selects_col("`slider`", "`image`", "`id` = $id");
            file::delete($data[0]['image']);
            $res = file::upload($_FILES['image']);
            if(!$res['error']){
                return data::update("`slider`", "`title` = '$title', `image` = '".$res['url']."', `url` = '$url', `pageid` = '$pageid', `content` = '$content'", "`id` = $id");
            }else{
                send_msg($res['message'], lang::$error);
            }
        }else{
            return data::update("`slider`", "`title` = '$title', `url` = '$url', `pageid` = '$pageid', `content` = '$content'", "`id` = $id");
        }
    }

    public static function delete_slider($id){
        $data = data::selects_col("`slider`", "`image`", "`id` = $id");
        file::delete($data[0]['image']);
        return data::delete("`slider`", "`id` = $id");
    }

    public static function set_imagebox($content, $url, $pageid, $createby){
        $res = file::upload($_FILES['image']);
        if(!$res['error']){
            return data::insertinto("`imagebox`", "`id`, `image`, `content`, `url`, `pageid`, `createby`, `creationdate`","NULL, '".$res['url']."', '$content', '$url', '$pageid', '$createby', CURRENT_TIMESTAMP");
        }
        else{
            send_msg($res['message'], lang::$error);
        }
    }

    public static function get_imagebox_by_id($id){
        $data = data::selects("`imagebox`", "`id` = $id");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_imagebox_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`imagebox`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`imagebox`", data::makeWhere($val1));
            }
            return false;
        }
    }

    public static function get_all_imagebox(){
        $data = data::selects("`imagebox`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_imagebox_join_page(){
        return data::selects_join("`pages`", "`imagebox`", "`imagebox`.*, `pages`.title as page_title", false, "`pages`.id = `imagebox`.pageid", false, false, false, true);
    }

    public static function update_imagebox($id, $content, $url, $pageid){
        if(!empty($_FILES['image'])){
            $data = data::selects_col("`slider`", "`image`", "`id` = $id");
            file::delete($data[0]['image']);
            $res = file::upload($_FILES['image']);
            if(!$res['error']){
                return data::update("`imagebox`", "`image` = '".$res['url']."', `content` = '$content', `url` = '$url', `pageid` = '$pageid'", "`id` = $id");
            }else{
                send_msg($res['message'], lang::$error);
            }
        }else{
            return data::update("`imagebox`", "`content` = '$content', `url` = '$url', `pageid` = '$pageid'", "`id` = $id");
        }
    }

    public static function delete_imagebox($id){
        $data = data::selects_col("`imagebox`", "`image`", "`id` = $id");
        file::delete($data[0]['image']);
        return data::delete("`imagebox`", "`id` = $id");
    }

    public static function set_file($createby){
        $res = file::upload($_FILES['file']);
        if($res){
            return data::insertinto("`file`", "`id`, `name`, `url`, `createdby`, `creationdate`", "NULL, '".$res['name']."', '".$res['url']."', '$createby', CURRENT_TIMESTAMP");
        }
        return false;
    }

    public static function get_file_by_id($id){
        $data = data::selects("`file`", "`id` = $id");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_all_file(){
        $data = data::selects("`file`", "");
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function update_file($id, $name, $url){
        return data::update("`file`", "`name` = '$name', `url` = '$url'", "`id` = $id");
    }

    public static function delete_file($id){
        $data = data::selects("`file`", "`id` = $id");
        if (count($data[0]) != 0) {
            if(file::delete($data['url'])){
                return data::delete("`file`", "`id` = $id");
            }
        } else {
            return false;
        }
        return false;
    }

    public static function set_user($emial, $password , $avatar , $username){
        return data::insertinto("`users`", "`ID`,`password`,`email`,`avatar`,`username`, `creationDate`", "NULL, '" . $password . "', '" . $emial . "', '" . $avatar . "', '" . $username . "', DATE_SUB( CURRENT_TIME(), INTERVAL 30 MINUTE)");
    }

    public static function edit_user($id,$username,$name,$sex,$address,$email,$cart,$sheba){
        return data::update("`users`", "`username` = '$username'  , `name` = '$name' ,  `sex`= '$sex' ,  `address` = '$address' ,  `email` = '$email' ,  `cart` = '$cart' , `sheba` = '$sheba'","`id`='$id'");
    }

    public static function set_user_login($id){
        return data::update("`users`", "`last_login` = DATE_SUB( CURRENT_TIME(), INTERVAL 30 MINUTE)","`id`='$id'");
    }

    public static function set_peyment($userid, $username, $Payment, $cartnumber, $cartname, $cartbank){
        data::insertinto("`payment_list`", "`userid`, `username`, `Payment`, `cartnumber`, `cartname`, `cartbank`", "$userid , '$username' , '$Payment' , '$cartnumber' , '$cartname' , '$cartbank'" );
    }

    public static function get_user_by_username_or_email($user){
        $data = data::selects('`users`', "`email` like '%$user%' OR  `username` like '%$user%'" );
        if (count($data[0]) != 0) {
            return $data;
        } else {
            return false;
        }
    }

    public static function get_user_by($val1, $val2 = false){
        if($val2 != false){
            return data::selects("`users`", "`$val1` = '$val2'");
        }else{
            if(is_array($val1)){
                return data::selects("`users`", data::makeWhere($val1));
            }
            return false;
        }
    }
}