<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 12:59
 */

// basic constants
define('ROOT_PATH', dirname(__FILE__));
define('SECURITY_SALT', 'Whistleblower uses first public appearance since surveillance leaks to defend decision and praise states that offered asylum.');

date_default_timezone_set('Europe/Moscow');
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

// config definitions
require_once(ROOT_PATH . '/config.php');

/*
 * in case we have error
 */
function showErrorPage($title, $content, $type = 500)
{
   // available error types
   $types = array(
         400 => 'HTTP/1.0 400 Bad Request',
         403 => 'HTTP/1.0 403 Forbidden',
         404 => 'HTTP/1.0 404 Not Found',
         405 => 'HTTP/1.0 405 Method Not Allowed',
         500 => 'HTTP/1.0 500 Internal Server Error',
         503 => 'HTTP/1.0 503 Service Unavailable'
   );

   // check error type
   $type = !isset($types[$type]) ? 400 : $type;

   // let`s get tpl
   $page = file_get_contents(ROOT_PATH . '/views/error_pages/' . $type . '.html');

   // printing error details
   $page = str_replace('{{title}}', $title, $page);
   $page = str_replace('{{content}}', $content, $page);

   // set header
   header($types[$type]);

   // printing output
   exit($page);
}


/*
 * establishing db connection
 */
$link = mysql_connect($config['db']['hostname'], $config['db']['username'], $config['db']['password']);
if(!$link){
   showErrorPage('Сервис недоступен', 'В данный момент наши специалисты проводят внеплановые технические работы, пжалуйста, попробуйте еще через пару минут. Приносим свои извинения за неудобства!', $type = 503);
}

/*
 * collations
 */
mysql_query("SET NAMES 'UTF8'", $link);
mysql_query("SET CHARACTER SET UTF8 ", $link);

// third party
require_once(ROOT_PATH . '/common/helper.php');
require_once(ROOT_PATH . '/common/billing.php');