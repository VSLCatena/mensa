<?php

use Illuminate\Support\HtmlString;

function formatDate($date, $addEnter = false, $withTime = true, $withDay = true){
    $days = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];

    $timestamp = strtotime($date);

    $dateFormat = $withTime?'d-m-Y H:i':'d-m-Y';

    $string = '';
    if($withDay)
        $string .= $days[date('N', $timestamp)-1] . ($addEnter?'<br />':' ');

    $string .= htmlspecialchars(date($dateFormat, $timestamp));
    if($addEnter)
        $string = new HtmlString($string);

    return $string;
}

function formatTime($date){
    $timestamp = strtotime($date);
    return htmlspecialchars(date('H:i', $timestamp));
}

function formatDateISO8601($date,$time){
    $timestamp = strtotime("$date" . " $time");
    return htmlspecialchars(date_format($timestamp,DATE_ISO8601));
}
