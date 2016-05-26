<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 14:07
 */

function getCommonInfo(){
   global $user;
   output(1, $user);
}


$op = get_param('op', 'striphtml');
switch($op){
   case 'getInfo':
      getCommonInfo();
      break;
   default:
      showErrorPage('Не найдено', 'Запрашиваемая страничка не найдена.', 404);
      break;
}
