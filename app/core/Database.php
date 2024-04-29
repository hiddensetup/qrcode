<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Database {

    public static $database = null;
    public static $db = null;

    public static function initialize() {
        mysqli_report(MYSQLI_REPORT_OFF);

        self::$database = new \mysqli(
            DATABASE_SERVER,
            DATABASE_USERNAME,
            DATABASE_PASSWORD,
            DATABASE_NAME
        );

        /* Debugging */
        if(self::$database->connect_error) {
            die('The connection to the database failed! Check the config.php file and make sure your database connection details are correct and your server is running.');
        }

        /* Mysql profiling */
        if(MYSQL_DEBUG) {
            self::$database->query("set profiling_history_size=100");
            self::$database->query("set profiling=1");
        }

        self::$database->set_charset('utf8mb4');

        self::initialize_helper();

        return self::$database;
    }

    public static function initialize_helper() {
        self::$db = new \Altum\Helpers\MysqliDb(self::$database);
        self::$db->returnType = 'object';
    }

    public static function close() {

        if(!self::$database) return;

        if(MYSQL_DEBUG) {
            $result = self::$database->query("show profiles");

            while($profile = $result->fetch_object()) {
                echo $profile->Query_ID . ' - ' . round($profile->Duration, 10)  . ' s - ' . $profile->Query . '<br />';
            }
        }

        self::$database->close();
    }
}
