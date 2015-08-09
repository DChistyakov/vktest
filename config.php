<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 09.08.2015
 * Time: 13:07
 */

$config = array();

// db
$config['db']['hostname'] = 'localhost';
$config['db']['username'] = 'vktest';
$config['db']['password'] = 'fGkbf74Fkjh47fhggGFs';
$config['db']['database'] = 'vk_test';

// table/db
$config['table']['user'] = '`vk_users`.`user`';
$config['table']['order'] = '`vk_orders`.`order`';
$config['table']['transaction'] = '`vk_transactions`.`transaction`';
$config['table']['session'] = '`vk_sessions`.`session`';

// system tax percent
$config['tax'] = 10;
