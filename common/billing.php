<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 13:24
 */

/*
 * Исполнитель кликает "выполнить" на заказе, ему на счет зачисляется сумма за вычетом комиссии системы.
 */

function process_payment($customer, $executor, $order, $tax)
{
   global $config;

   // let`s check customer
   if(!is_int($customer) OR !is_user_exists($customer)){
      return array('status' => 0, 'data' => array('title' => 'Заказчик не найден.', 'code' => 1));
   }

   // let`s check executor
   if(!is_int($executor) OR !is_user_exists($executor)){
      return array('status' => 0, 'data' => array('title' => 'Исполнитель не найден.', 'code' => 2));
   }

   // let`s check order
   if(!is_int($order) OR !is_order_exists($order)){
      return array('status' => 0, 'data' => array('title' => 'Заказ не найден.', 'code' => 3));
   }

   // tax validator (could be 0)
   if(!is_numeric($tax) OR $tax < 0 OR $tax >= 100){
      return array('status' => 0, 'data' => array('title' => 'Неверная комиссия операции.', 'code' => 5));
   }

   $order_details = get_order_details($order);

   // amount validator (must be higher than 0)
   if(!is_numeric($order_details['amount4customer']) OR $order_details['amount4customer'] <= 0 OR $order_details['amount4customer'] >= 1000000){
      return array('status' => 0, 'data' => array('title' => 'Неверная сумма операции.', 'code' => 4));
   }

   // calculating
   $amount4system = $tax / 100 * $order_details['amount4customer'];
   $amount4executor = $order_details['amount4customer'] - $amount4system;

   // performing
   query('SET AUTOCOMMIT=0');
   query('START TRANSACTION');

   query('UPDATE ' . $config['table']['user'] . '
      SET   balance = balance - "' . $order_details['amount4customer'] . '",
            expenses_today = expenses_today + "' . $order_details['amount4customer'] . '",
            expenses_month = expenses_month + "' . $order_details['amount4customer'] . '",
            expenses_total = expenses_total + "' . $order_details['amount4customer'] . '"
      WHERE id = "' . $customer . '"');

   query('UPDATE ' . $config['table']['user'] . '
      SET   balance = balance + "' . $amount4executor . '",
            income_today = income_today + "' . $amount4executor . '",
            income_month = income_month + "' . $amount4executor . '",
            income_total = income_total + "' . $amount4executor . '"
      WHERE id = "' . $executor . '"');

   query('UPDATE ' . $config['table']['user'] . '
      SET   balance = balance + "' . $amount4system . '",
            income_today = income_today + "' . $amount4system . '",
            income_month = income_month + "' . $amount4system . '",
            income_total = income_total + "' . $amount4system . '"
      WHERE id = 1');

   query('UPDATE ' . $config['table']['order'] . '
      SET   `status` = "done",
            status_at_dt = NOW(),
            amount4executor = "' . $amount4executor . '",
            amount4system = "' . $amount4system . '",
            executor_id = "' . $executor . '"
      WHERE id = "' . $order . '"');

   query('INSERT INTO ' . $config['table']['transaction'] . '
      SET   created_at_d = CURDATE(),
            created_at_dt = NOW(),
            customer_id = "' . $customer . '",
            executor_id = "' . $executor . '",
            order_id = "' . $order . '",
            amount4customer = ' . $order_details['amount4customer'] . ',
            amount4executor = "' . $amount4executor . '",
            amount4system = "' . $amount4system . '"');

   if(mysql_error()){
      query('ROLLBACK');
      return array('status' => 0, 'data' => array('title' => 'Ошибка проведения транзакции.', 'code' => 6));
   } else{
      query('COMMIT');
      return array('status' => 1);
   }
}