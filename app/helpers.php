<?php

use Illuminate\Support\HtmlString;

function formatDate($date, $addEnter = false){
    $days = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];

    $timestamp = strtotime($date);
    $string = $days[date('N', $timestamp)-1] . ($addEnter?'<br />':' ') . htmlspecialchars(date('d-m-Y H:i', $timestamp));
    if($addEnter)
        $string = new HtmlString($string);

    return $string;
}