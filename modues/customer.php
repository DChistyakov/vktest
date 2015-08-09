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

   // limits
   $page = intval(get_param('page'));
   $page = $page - 1;
   $start = ($page < 0) ? 0 : $page * 5; // 5 rows per page

   // reading data
   $sql = sprintf('SELECT * FROM ' . $config['table']['order'] . ' WHERE customer_id = "%s" AND deleted_is = "no" ORDER BY id DESC LIMIT %s, 5', intval($user['id']), $start);
   $data = qfa($sql);

   // reading metadata
   $sql = sprintf('SELECT COUNT(id) AS items FROM ' . $config['table']['order'] . ' WHERE customer_id = "%s" AND deleted_is = "no"', intval($user['id']));
   $meta = qfr($sql);

   $meta = array(
         'totalCount' => $meta['items'],
         'currentPage' => $page
   );

   output(1, $data, $meta);
}

function deleteItem()
{
   $id = intval(get_param('id'));

   output(1);
}

$op = get_param('op', 'striphtml');
switch($op){
   case 'getOrders':
      getOrders();
      break;

   case 'deleteItem':
      deleteItem();
      break;

   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}