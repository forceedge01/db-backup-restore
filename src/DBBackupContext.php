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

    /**
     * @BeforeSuite
     */
    public static function BackupDB()
    {
        if (!self::$config->autoBackup()) {
            return;
        }
    }

    /**
     * @AfterSuite
     */
    public static function restoreDB()
    {
        if (!self::$config->autoRestore()) {
            return;
        }
    }
}
