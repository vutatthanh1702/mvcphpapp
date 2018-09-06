<?php

require_once '/var/www/html/thanh/mvc/dispatcher.php';
$dispatcher = new Dispatcher();
$dispatcher->setSystemRoot('/var/www/html/thanh/mvc');
$dispatcher->dispatch();

?>
