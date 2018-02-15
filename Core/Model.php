<?php
namespace Core;
use PDO;
use App\Config\Db as DbConfig;

/**
 * Class Model
 * @package Core
 * @var DbConfig DbConfig
 */
abstract class Model
{
    protected static function getDB()
    {
        static $db = null;
        if ($db === null) {
            try {
                $dsn = 'mysql:host=' . DbConfig::DB_HOST . ';dbname=' . DbConfig::DB_NAME . ';charset=utf8';
                $db = new PDO($dsn, DbConfig::DB_USER, DbConfig::DB_PASS);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            }
            catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}
