<?php

define('START_P_TIME', microtime(true));
define('START_P_MEM', memory_get_usage());


Kohana::$log->attach(new Log_FirePHP);
