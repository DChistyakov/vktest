<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 12:58
 */

require_once('../init.php');

// module detector
$module = get_param('module', 'striphtml');

// is user logged in
$user_id = is_logged_in();
if(!$user_id AND $module != 'login'){
   output(0, array('title' => 'Необходимо авторизация', 'code' => 1000));
}

// let`s read user`s data
$user = get_user_by_id($user_id);

// we are good to go
switch($module){
   case 'login':
      require_once(ROOT_PATH . '/modules/login.php');
      break;

   default:
      echo "нет экшена";
      break;
}