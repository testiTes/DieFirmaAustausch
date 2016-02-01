<?php

// Datenbankzugriff
if (DEV == TRUE) {
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWD', '');
    define('DB_NAME', 'bbqfirma');
} else {
    //define('DB_SERVER', '213.136.73.230');
    define('DB_SERVER', '192.168.2.80');
    define('DB_USER', 'bbqfirma');
    define('DB_PASSWD', '%n232cYk');
    define('DB_NAME', 'bbqfirma');
}

// Zeitzone
date_default_timezone_set('Europe/Berlin');
