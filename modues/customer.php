<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 15:43
 */

function getOrders()
{
   global $user, $config;

   $sql = sprintf('SELECT * FROM ' . $config['table']['order'] . ' WHERE customer_id = "%s" AND deleted_is = "no"', intval($user['id']));
   $data = qfa($sql);
   output(1, $data);
}


$op = get_param('op', 'striphtml');
switch($op){
   case 'getOrders':
      getOrders();
      break;
   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}