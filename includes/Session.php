<?php

// Class Name: Session

class Session{


  // Session Start Method
  public static function init(){
    if(!self::isSessionOpen()){
      if (version_compare(phpversion(), '5.4.0', '<')) {
        if (session_id() == '') {
          session_start();
        }
      }else{
        if (session_status() == PHP_SESSION_NONE) {
          session_start();
        }
      }
    }
  }

  // Check if session is open
  public static function isSessionOpen(){
    if (version_compare(phpversion(), '5.4.0', '<')) {
      return (session_id() != '');
    } else {
      return (session_status() == PHP_SESSION_ACTIVE);
    }
  }


  // Session Set Method
  public static function set($key, $val){
    $_SESSION[$key] = $val;
  }

  // Session Get Method todos los datos
  public static function getAll(){
    return $_SESSION;
  }

  // Session Get Method
  public static function get($key){
    if (isset($_SESSION[$key])) {
      return $_SESSION[$key];
    }else{
      return false;
    }
  }



  // User logout Method
  public static function destroy(){
    session_destroy();
    session_unset();
  }

  
}
