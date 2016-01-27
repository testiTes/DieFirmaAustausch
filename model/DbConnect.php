<?php

class DbConnect {

    private static $conn;

    public static function connect() {
        /*
         * Design Pattern: Singleton
         * wenn die Verbindung zur db schon vorhanden ist
         * soll Methode connect() eine Verbindung nicht nochmal
         * erstellen, also max Anzahl Connections = 1
         */

        if (!self::$conn) {
            try {
                self::$conn = new PDO('mysql:host=' . DB_SERVER . ';charset=utf8' . ';dbname=' . DB_NAME, DB_USER, DB_PASSWD, [PDO::ATTR_PERSISTENT => TRUE, PDO::ATTR_ERRMODE => TRUE, PDO::ATTR_EMULATE_PREPARES => FALSE]);
            } catch (Exception $exc) {
                throw new Exception('Konnte mich nicht mit db verbinden DB_SERVER:' . DB_SERVER . ' oder SQL Fehler ->phplogger.txt');
            }
        }
        return self::$conn;
    }

}

?>
