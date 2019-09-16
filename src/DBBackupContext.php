<?php

namespace Genesis\DBBackup;

use Behat\Behat\Context\Context;

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

    private static $backupDone = false;

    private static $backupError = false;

    private static $restoreError = false;

    /**
     * @BeforeScenario
     * @param mixed $suite
     * @param mixed $scenario
     */
    public function BackupDB($scenario)
    {
        if (self::$backupDone) {
            return;
        }

        if (!self::$config->autoBackup()) {
            return;
        }

        self::$config->addDatabaseConfig('filename', 'backup-' . date('y-m-d-h-i-s') . '.sql');

        echo 'Backing up database...' . PHP_EOL;
        foreach (self::$config->getDatabaseConfigs() as $connection => $config) {
            $class = self::getHandler($config['engine']);
            $cmd = $class::backupCommand(self::$config);
            echo $cmd . PHP_EOL;
            $output = '';
            $statusCode = 0;
            echo exec($cmd, $output, $statusCode);

            if ($statusCode !== 0) {
                self::$backupError = true;
                exec('rm ' . self::$config->getBackupPath() . DIRECTORY_SEPARATOR . $config['filename']);
                throw new \Exception('Unable to backup, something went wrong.');
            }
        }

        self::$backupDone = true;
        echo 'Backup complete';
    }

    /**
     * @AfterSuite
     * @param mixed $suite
     */
    public static function restoreDB($suite)
    {
        if (self::$config->autoBackup() && self::$config->autoRestore() && !self::$backupError) {
            echo 'Restoring database...' . PHP_EOL;
            foreach (self::$config->getDatabaseConfigs() as $connection => $config) {
                $class = self::getHandler($config['engine']);

                $dropCmd = $class::dropDatabaseCommand(self::$config);
                echo $dropCmd . PHP_EOL;
                echo shell_exec($dropCmd);

                $createCmd = $class::createDatabaseCommand(self::$config);
                echo $createCmd . PHP_EOL;
                echo shell_exec($createCmd);

                $cmd = $class::restoreCommand(self::$config);
                echo $cmd . PHP_EOL;
                $output = '';
                $statusCode = 0;
                echo exec($cmd, $output, $statusCode);

                if ($statusCode !== 0) {
                    self::$restoreError = true;
                    throw new \Exception('Unable to restore, something went wrong.');
                }
            }
            echo 'Restore complete' . PHP_EOL;
        }

        if (self::$config->autoRemove() && self::$restoreError === false) {
            echo 'Removing auto generated files.' . PHP_EOL;
            foreach (self::$config->getDatabaseConfigs() as $connection => $config) {
                exec(sprintf(
                    'rm %s',
                    self::$config->getBackupPath()  . DIRECTORY_SEPARATOR . $config['filename']
                ));
            }
        }
    }

    /**
     * @AfterSuite
     * @param mixed $suite
     */
    public static function keepBackupsClean($suite)
    {
        if (self::$config->keepClean() && self::$backupError === false) {
            echo 'Cleaning all backup files...' . PHP_EOL;
            exec(sprintf(
                'rm -rf %s*',
                self::$config->getBackupPath()  . DIRECTORY_SEPARATOR
            ));
        }
    }

    /**
     * @return string
     */
    private static function getHandler(string $handlerRef): string
    {
        return __NAMESPACE__ . '\\Handler\\' . strtolower($handlerRef);
    }
}
