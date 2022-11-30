<?php

namespace App\Traits;

trait Severity {
    // 0       Emergency: system is unusable
    // 1       Alert: action must be taken immediately
    // 2       Critical: critical conditions
    // 3       Error: error conditions
    // 4       Warning: warning conditions
    // 5       Notice: normal but significant condition
    // 6       Informational: informational messages
    // 7       Debug: debug-level messages 
    public static $EMERGENCY = 0
    public static $ALERT = 1;
    public static $CRITICAL = 2
    public static $ERROR = 3
    public static $WARNING = 4
    public static $NOTICE = 5
    public static $INFORMATIONAL = 6
    public static $DEBUG = 7
}
