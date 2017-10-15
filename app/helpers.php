<?php
function formatDate($date, $addEnter = false){
    $days = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];

    $timestamp = strtotime($date);
    return $days[date('N', $timestamp)-1] . ($addEnter?'<br />':' ') . date('d-m-Y H:i', $timestamp);
}