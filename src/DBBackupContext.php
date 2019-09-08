<?php

namespace Genesis\DBBackup;

use Behat\Behat\Context\Context;
use Behat\Testwork\ServiceContainer\Configuration\ConfigurationLoader;

/**
 * DBBackup class.
 */
class DBBackupContext implements Context
{
    /**
     * @var Config
     */
    public static $config;

    public static $backupFile;

    public static $fileName;

    /**
     * @BeforeSuite
     * @param mixed $suite
     */
    public static function BackupDB($suite)
    {
        // BeforeScenario with a flag can do it.

        self::$fileName = 'backup-' . date('y-m-d-h-i-s') . '.sql';

        if (!self::$config->autoBackup()) {
            return;
        }

        $cmd = sprintf(
            'mysqldump --user="%s" --password="%s" --host="%s" --port="%d" %s > %s',
            self::$config->getDatabaseConfig('username', 'root'),
            self::$config->getDatabaseConfig('password', 'root'),
            self::$config->getDatabaseConfig('host', 'localhost'),
            self::$config->getDatabaseConfig('port', 3306),
            self::$config->getDatabaseConfig('dbname'),
            self::$config->getBackupPath() . '/' . self::$fileName
        );

        echo 'Backing up database...' . PHP_EOL;
        echo $cmd . PHP_EOL;
        // exec($cmd);
        echo 'Backup complete';
    }

    /**
     * @AfterSuite
     * @param mixed $suite
     */
    public static function restoreDB($suite)
    {
        if (!self::$config->autoBackup() || !self::$config->autoRestore()) {
            return;
        }

        $cmd = sprintf(
            'mysql -u "%s" -p{$password} -h "%s" --port="%d" %s < %s',
            self::$config->getDatabaseConfig('username', 'root'),
            self::$config->getDatabaseConfig('password', 'root'),
            self::$config->getDatabaseConfig('host', 'localhost'),
            self::$config->getDatabaseConfig('port', 3306),
            self::$config->getDatabaseConfig('dbname'),
            self::$config->getBackupPath() . '/' . self::$fileName
        );

        echo 'Restoring database...' . PHP_EOL;
        echo $cmd . PHP_EOL;
        // exec($cmd);
        echo 'Restore complete';

        if (self::$config->autoRemove()) {
            exec(sprintf('rm %s', self::$config->getBackupPath()  . '/' . self::$fileName));
        }
    }

    private static function getYamlConfiguration()
    {
        $config = new ConfigurationLoader('BEHAT_PARAMS', getcwd() . '/behat.yml'))->loadConfiguration();

        return $config[0]['extensions']['Genesis\\DBBackup\\Extension'];
    }
}
