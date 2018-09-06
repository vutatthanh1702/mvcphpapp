<?php

function e($str, $charset= 'UTF-8') {
    print(htmlspecialchars($str, ENT_QUOTES, $charset));
}

function format($datetime, $format='yyyy /MM/dd' ) { 
    $ts=strtotime($datetime);
    print(date($format, $ts));
}
