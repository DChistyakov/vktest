<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 10.08.2015
 * Time: 12:37
 */

function getOrders()
{
   global $config;

   // limits
   $page = intval(get_param('page'));
   $page = $page - 1;
   $start = ($page < 0) ? 0 : $page * 5; // 5 rows per page

   // reading data
   $sql = sprintf('SELECT * FROM ' . $config['table']['order'] . ' WHERE `status` = "new" AND deleted_is = "no" ORDER BY id DESC LIMIT %s, 5', $start);
   $data = qfa($sql);

   // reading metadata
   $sql = 'SELECT COUNT(id) AS items FROM ' . $config['table']['order'] . ' WHERE `status` = "new" AND deleted_is = "no"';
   $meta = qfr($sql);

   $meta = array(
         'totalCount' => $meta['items'],
         'currentPage' => $page + 1,
         'pageCount' => ceil($meta['items'] / 5)
   );

   output(1, $data, $meta);
}

function reserveOrder()
{
   global $config, $user;

   $id = intval(get_param('id'));

   $sql = sprintf('UPDATE   ' . $config['table']['order'] . '
                     SET      `status` = "reserved",
                              status_at_dt = NOW(),
                              executor_id = "%s"
                     WHERE    deleted_is = "no"
                              AND `status` = "new"
                              AND id = "%s"',
         intval($user['id']),
         intval($id));

   if(query($sql)){
      // payment
      $res = process_payment($id, $config['tax']);

      if($res['status'] < 1){
         // rollback
         $sql = sprintf('UPDATE   ' . $config['table']['order'] . '
                     SET      `status` = "new",
                              status_at_dt = NOW(),
                              executor_id = "0"
                     WHERE    deleted_is = "no"
                              AND `status` = "reserved"
                              AND id = "%s"',
               intval($id));
         query($sql);

         print_jsond($res);
      }

      output(1);
   }

   output(0, array('title' => 'Задание не найдено или уже было выполнено', 'code' => 40));
}

$op = get_param('op', 'striphtml');
switch($op){
   case 'getOrders':
      getOrders();
      break;

   case 'reserveOrder':
      reserveOrder();
      break;

   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}