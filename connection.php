<?php

/**
 * Created by PhpStorm.
 * User: yura
 * Date: 06.02.17
 * Time: 21:53
 */
class Db
{
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
            //self::$instance = new PDO('mysql:host=localhost;dbname=booker', 'root', '123', $pdo_options);
            self::$instance = new PDO('mysql:host=localhost;dbname=bookerbackup', 'root', '1', $pdo_options);
        }
        return self::$instance;
    }

}
