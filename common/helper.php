<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 08.08.2015
 * Time: 12:47
 */


/*
 * custom query (timers, loggers, etc)
 */
function query($query)
{
   $start_time = microtime(true);
   $result = mysql_query($query);
   $end_time = microtime(true);

   return $result;
}

function qfr($query)
{
   $result = query($query);

   if(!$result){
      $message = 'Неверный запрос: ' . mysql_error() . "\n";
      $message .= '<br>Запрос целиком: ' . $query;
      die($message);
   }

   return mysql_fetch_assoc($result);
}

function qfa($query)
{
   $result = query($query);

   if(!$result){
      $message = 'Неверный запрос: ' . mysql_error() . "\n";
      $message .= '<br>Запрос целиком: ' . $query;
      die($message);
   }

   $data = array();
   while ($row = mysql_fetch_assoc($result)) {
      $data[] = $row;
   }

   return $data;
}

function qfnr($query)
{
   $result = query($query);

   if(!$result){
      $message = 'Неверный запрос: ' . mysql_error() . "\n";
      $message .= '<br>Запрос целиком: ' . $query;
      die($message);
   }

   return mysql_num_rows($result);
}

function is_user_exists($id)
{
   global $config;

   $sql = sprintf('SELECT id FROM ' . $config['table']['user'] . ' WHERE id = "%s"', intval($id));
   return qfnr($sql) > 0 ? true : false;
}

function is_username_exists($username)
{
   global $config;

   $sql = sprintf('SELECT id FROM ' . $config['table']['user'] . ' WHERE username_bin_hash = UNHEX(MD5("%s"))', mysql_real_escape_string($username));
   return qfnr($sql) > 0 ? true : false;
}

function get_user_by_id($id)
{
   global $config;

   $sql = sprintf('SELECT * FROM ' . $config['table']['user'] . ' WHERE id = "%s"', intval($id));
   return qfr($sql);
}


function get_user_by_username($username)
{
   global $config;

   $sql = sprintf('SELECT * FROM ' . $config['table']['user'] . ' WHERE username_bin_hash = UNHEX(MD5("%s"))', mysql_real_escape_string($username));
   return qfr($sql);
}

function get_order_details($id)
{
   global $config;

   $sql = sprintf('SELECT * FROM ' . $config['table']['order'] . ' WHERE id = "%s"', intval($id));
   return qfr($sql);
}

function session_create($user_id, $user_ip, $lifetime, $user_agent, $key)
{
   global $config;

   $sql = sprintf('INSERT INTO ' . $config['table']['session'] . '
                     SET   user_id = "%s",
                           user_ip = "%s",
                           created_at_dt = NOW(),
                           expire_at_dt = NOW() + INTERVAL %s SECOND,
                           user_agent = "%s",
                           session_key = "%s",
                           session_key_bin_hash = UNHEX(MD5("%s"))',
         intval($user_id),
         mysql_real_escape_string($user_ip),
         intval($lifetime),
         mysql_real_escape_string($user_agent),
         mysql_real_escape_string($key),
         mysql_real_escape_string($key));

   $result = query($sql);

   if(!$result){
      return array('status' => 0, 'data' => array('title' => 'Не удалось создать сессию.', 'code' => 100));
   }

   return array('status' => 1, 'data' => array('session_bid' => mysql_insert_id()));
}

function session_revoke()
{
   global $config;

   // session data
   if((isset($_COOKIE['session_bid']) AND isset($_COOKIE['session_key']))
         OR (get_param('session_bid') AND get_param('session_bid') != '' AND get_param('session_key') AND get_param('session_key') != '')){
      $session_bid = isset($_COOKIE['session_bid']) ? $_COOKIE['session_bid'] : get_param('session_bid');
      $session_key = isset($_COOKIE['session_bid']) ? $_COOKIE['session_key'] : get_param('session_key');

      $sql = sprintf('SELECT id FROM ' . $config['table']['session'] . ' WHERE session_key_bin_hash = UNHEX(MD5("%s")) AND id = "%s"', mysql_real_escape_string($session_key), intval($session_bid));
      $session_data = qfr($sql);

      // remove session
      $sql = 'UPDATE ' . $config['table']['session'] . ' SET user_id = 0, expire_at_dt = NOW() - INTERVAL 1 DAY WHERE id = ' . $session_data['id'];
      query($sql);
   }

   setcookie("session_key", 'deleted', time(), "/", 'vk.dchistyakov.ru');
   setcookie("session_bid", 0, time(), "/", 'vk.dchistyakov.ru');

   return array('status' => 1);
}

function is_logged_in()
{
   global $config;

   if(!isset($_COOKIE['session_bid']) AND (!get_param('session_bid') OR get_param('session_bid') == '')){
      return false;
   }

   if(!isset($_COOKIE['session_key']) AND (!get_param('session_key') OR get_param('session_key') == '')){
      return false;
   }

   $session_bid = isset($_COOKIE['session_bid']) ? $_COOKIE['session_bid'] : get_param('session_bid');
   $session_key = isset($_COOKIE['session_bid']) ? $_COOKIE['session_key'] : get_param('session_key');

   // session data
   $sql = sprintf('SELECT * FROM ' . $config['table']['session'] . ' WHERE session_key_bin_hash = UNHEX(MD5("%s")) AND id = "%s"', mysql_real_escape_string($session_key), intval($session_bid));
   $session_data = qfr($sql);

   // is expired
   if(strtotime($session_data['expire_at_dt']) < time()){
      session_revoke();
      return false;
   }

   // useragent (just to show that i understand that this should be encrypted)
   if(md5($_SERVER['HTTP_USER_AGENT']) != md5($session_data['user_agent'])){
      session_revoke();
      return false;
   }

   return $session_data['user_id'];
}

function get_param($var, $check_type = '', $default_value = false)
{
   global $_GET, $_POST, $argv, $GLOBALS;
   $result = $default_value !== false ? $default_value : false;
   if(isset($GLOBALS) && isset($GLOBALS['system_vars']) && isset($GLOBALS['system_vars'][$var])){
      $result = $GLOBALS['system_vars'][$var];
   }
   if(isset($argv)){
      for($i = 1; $i < count($argv); $i++){
         $val = explode("=", $argv[$i]);
         if(isset($val[1])){
            $vals[ltrim($val[0], "-")] = $val[1];
         } else{
            $vals[ltrim($argv[$i], "-")] = true;
         }
      }
      if(isset($vals[$var])){
         $result = $vals[$var];
      }
   }
   if(isset($_GET) && isset($_GET[$var])){
      $result = $_GET[$var];
   }
   if(isset($_POST) && isset($_POST[$var])){
      $result = $_POST[$var];
   }

   if(preg_match('%(SELECT|UPDATE|DROP|DELETE|INTO|TRUNCATE|FROM|GRANT|UNION)\s%smi', $result)){
      return '';
   }

   if($check_type == 'striphtml'){
      return strip_tags($result);
   } elseif($check_type == 'safehtml'){
      $patterns = array("/\&/", "/%/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
      $replacements = array("&amp;", "&#37;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
      $string = preg_replace($patterns, $replacements, $result);
      return $string;
   } elseif($check_type == 'trim'){
      return trim(stripslashes(strval($result)));
   } else{
      return $result;
   }
}

function print_jsond($array)
{
   exit(json_encode($array));
}

function output($status, $payload = array(), $meta = array())
{
   print_jsond(array('status' => $status, 'data' => $payload, 'meta' => $meta));
}