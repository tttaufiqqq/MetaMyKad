<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $config = App::config('database', []);
        // charset omitted from DSN intentionally: the init command below is the
        // single source of truth for the connection charset. All tables and stored
        // procedures use the MySQL 9.x default (utf8mb4_0900_ai_ci), so no explicit
        // collation override is needed — matching avoids collation-mismatch errors.
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s',
            $config['host'],
            $config['port'],
            $config['name']
        );

        self::$connection = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        ]);

        return self::$connection;
    }
}
