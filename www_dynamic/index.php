<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 12:58
 */

require_once('../init.php');

// accessible from static via AJAX
header('Access-Control-Allow-Origin: http://static.vk.dchistyakov.ru');

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

   case 'common':
      require_once(ROOT_PATH . '/modules/common.php');
      break;

   case 'customer':
      require_once(ROOT_PATH . '/modules/customer.php');
      break;

   case 'executor':
      require_once(ROOT_PATH . '/modules/executor.php');
      break;

   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}