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
         'currentPage' => $page + 1,
         'pageCount' => ceil($meta['items'] / 5)
   );

   output(1, $data, $meta);
}

function deleteItem()
{
   global $config, $user;

   $id = intval(get_param('id'));

   $sql = sprintf('UPDATE   ' . $config['table']['order'] . '
                     SET      deleted_is = "yes",
                              deleted_at_dt = NOW()
                     WHERE    customer_id = "%s"
                              AND deleted_is = "no"
                              AND id = "%s"
                     ORDER BY id',
         intval($user['id']),
         intval($id));
   if(query($sql)){
      output(1);
   }

   output(0, array('title' => 'Задание не найдено', 'code' => 33));
}

function createItem()
{
   global $config, $user;

   $title = get_param('title', 'striphtml');
   $descr = get_param('descr', 'striphtml');
   $amount = floatval(get_param('amount'));

   if(!$title OR $title == ''){
      output(0, array('title' => 'Не указано название задания', 'code' => 30));
   }

   if(!$descr OR $descr == ''){
      output(0, array('title' => 'Не указано описание задания', 'code' => 31));
   }

   if(!$amount OR $amount <= 0){
      output(0, array('title' => 'Не указано вознаграждение за задания', 'code' => 32));
   }

   $sql = sprintf('INSERT INTO ' . $config['table']['order'] . '
            SET   created_at_d = CURDATE(),
                  created_at_dt = NOW(),
                  customer_id = "%s",
                  title = "%s",
                  description = "%s",
                  amount4customer = "%s"',
         intval($user['id']),
         mysql_real_escape_string($title),
         mysql_real_escape_string($descr),
         mysql_real_escape_string($amount));

   if(query($sql)){
      output(1, array('id' => mysql_insert_id()));
   }

   output(0, array('title' => 'Ошибка записи в БД', 'code' => 1001));
}

$op = get_param('op', 'striphtml');
switch($op){
   case 'getOrders':
      getOrders();
      break;

   case 'deleteItem':
      deleteItem();
      break;

   case 'createItem':
      createItem();
      break;

   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}