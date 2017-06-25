<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 5/17/2017
 * Time: 6:45 PM
 */

class session{

    private static function start(){
        if(!self::check()){
            @session_start();
            return true;
        }
        return true;
    }

    public static function set($key, $value){
        if(self::start()){
            $_SESSION[$key] = $value;
            return true;
        }
        return false;
    }

    public static function get($name, $key = false){
        if(self::start()){
            if($key != false){
                if(isset($_SESSION[$name][$key]))
                    return $_SESSION[$name][$key];
            }else{
                if(isset($_SESSION[$name]))
                    return $_SESSION[$name];
            }
        }
        return false;
    }

    private static function check(){
        if(!isset($_SESSION))
            return false;
        return true;
    }

    public static function display(){
        if(self::start()){
            echo '<pre>';
            echo print_r($_SESSION);
            echo '</pre>';
        }
    }
}