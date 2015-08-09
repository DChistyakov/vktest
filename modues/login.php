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


}

function logout()
{

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