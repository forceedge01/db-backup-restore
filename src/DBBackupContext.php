<?php

namespace DBBackup;

/**
 * DBBackup class.
 */
class DBBackupContext
{
    /**
     * @var Config
     */
    public static $config;

    public static $backupFile;

    /**
     * @BeforeSuite
     */
    public static function BackupDB()
    {
        if (!self::$config->autoBackup()) {
            return;
        }

        $cmd = sprintf(
            'mysqldump --user="%s" --password="%s" --host="%s" --port="%d" %s > %s',
            $this->$config->getDatabaseConfig('username', 'root'),
            $this->$config->getDatabaseConfig('password', 'root'),
            $this->$config->getDatabaseConfig('host', 'localhost'),
            $this->$config->getDatabaseConfig('port', 3306),
            $this->$config->getDatabaseConfig('dbname'),
            $this->$config->getBackupPath()
        );

        echo 'Backing up database...' . PHP_EOL;
        echo $cmd . PHP_EOL;
        // exec($cmd);
        echo 'Backup complete';
    }

    /**
     * @AfterSuite
     */
    public static function restoreDB()
    {
        if (!self::$config->autoRestore()) {
            return;
        }

        $cmd = sprintf(
            'mysql -u "%s" -p{$password} -h "%s" --port="%d" %s < %s',
            $this->$config->getDatabaseConfig('username', 'root'),
            $this->$config->getDatabaseConfig('password', 'root'),
            $this->$config->getDatabaseConfig('host', 'localhost'),
            $this->$config->getDatabaseConfig('port', 3306),
            $this->$config->getDatabaseConfig('dbname'),
            $this->$config->getBackupPath()
        );

        echo 'Restoring database...' . PHP_EOL;
        echo $cmd . PHP_EOL;
        // exec($cmd);
        echo 'Restore complete';
    }
}
