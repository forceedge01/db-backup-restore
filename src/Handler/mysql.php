<?php

namespace Genesis\DBBackup\Handler;

use Genesis\DBBackup\Config;
use Genesis\DBBackup\Interfaces\HandlerInterface;

/**
 * mysql class.
 */
class mysql implements HandlerInterface
{
    /**
     * @param Config $config
     *
     * @return string
     */
    public static function backupCommand(Config $config): string
    {
        return sprintf(
            'mysqldump -u"%s" -p"%s" -h"%s" --port="%d" "%s" > %s',
            $config->getDatabaseConfig('username', 'root'),
            $config->getDatabaseConfig('password', 'root'),
            $config->getDatabaseConfig('host', 'localhost'),
            $config->getDatabaseConfig('port', 3306),
            $config->getDatabaseConfig('dbname'),
            $config->getBackupPath() . DIRECTORY_SEPARATOR . $config->getConfig('filename')
        );
    }

    /**
     * @param Config $config
     *
     * @return string
     */
    public static function restoreCommand(Config $config): string
    {
        return sprintf(
            '%s %s < %s',
            self::connectionString($config),
            $config->getDatabaseConfig('dbname'),
            $config->getBackupPath() . DIRECTORY_SEPARATOR . $config->getConfig('filename')
        );
    }

    /**
     * @param Config $config
     *
     * @return string
     */
    public static function dropDatabaseCommand(Config $config): string
    {
        return sprintf(
            '%s -e "DROP DATABASE \`%s\`"',
            self::connectionString($config),
            $config->getDatabaseConfig('dbname')
        );
    }

    /**
     * @param Config $config
     *
     * @return string
     */
    public static function createDatabaseCommand(Config $config): string
    {
        return sprintf(
            '%s -e "CREATE DATABASE \`%s\`"',
            self::connectionString($config),
            $config->getDatabaseConfig('dbname')
        );
    }

    /**
     * @param Config $config
     *
     * @return string
     */
    public static function connectionString(Config $config): string
    {
        return sprintf(
            'mysql -u"%s" -p"%s" -h"%s" --port="%d"',
            $config->getDatabaseConfig('username', 'root'),
            $config->getDatabaseConfig('password', 'root'),
            $config->getDatabaseConfig('host', 'localhost'),
            $config->getDatabaseConfig('port', 3306)
        );
    }
}
