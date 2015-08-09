<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 13:07
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
