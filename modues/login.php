<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 13:09
 */

function login()
{
   global $config;

   // reading params
   $username = get_param('username', 'striphtml');

   // is this username exists
   $res = is_username_exists($username);
   if($res){
      // reading user data
      $user = get_user_by_username($username);

      // logging
      $lifetime = 30 * 24 * 60 * 60;

      $offset = rand(0, strlen(SECURITY_SALT) - 10); // random value
      $length = rand(5, 10);
      $session_key = md5(substr($offset, $length) . time() . getmypid());

      $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

      // creating session
      $result = session_create($user['id'], $_SERVER["REMOTE_ADDR"], $lifetime, $user_agent, $session_key);

      if($result['status'] < 1){
         print_jsond($result);
      }

      // setting cookies
      setcookie("session_key", $session_key, time() + $lifetime, "/", 'vk.dchistyakov.ru');
      setcookie("session_bid", $result['data']['session_bid'], time() + $lifetime, "/", 'vk.dchistyakov.ru');

      output(1, array('session_id' => $result['data']['session_bid']));
   }

   output(0, array('title' => 'Неверное имя пользователя.', 'code' => 10));
}

function logout()
{
   $result = session_revoke();

   if($result['status'] < 1){
      print_jsond($result);
   }

   output(1);
}

$op = get_param('op', 'striphtml');
switch($op){
   case 'login':
      login();
      break;
   case 'logout':
      logout();
      break;
   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}