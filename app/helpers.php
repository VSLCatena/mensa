<?php
function formatDate($date){
    $days = ['maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'];

    $timestamp = strtotime($date);
    return $days[date('N', $timestamp)] . ' ' . date('d-m-Y H:i', $timestamp);
}