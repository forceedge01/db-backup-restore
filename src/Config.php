<?php

namespace Genesis\DBBackup;

/**
 * Config class.
 */
class Config
{
    public static $databaseConfigs = [];

    public static $config = [];

    public static $connection;

    public function __construct(
        array $databaseConfigs,
        string $backupPath,
        bool $autoBackup = false,
        bool $autoRestore = false,
        bool $autoRemove = false,
        bool $keepClean = false
    ) {
        self::$databaseConfigs = $databaseConfigs;
        $this->backupPath = realpath($backupPath);
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
        $this->autoRemove = $autoRemove;
        $this->keepClean = $keepClean;

        if (! $this->backupPath) {
            throw new \Exception("Path $backupPath not found.");
        }
    }

    public function setActiveConfig($connection)
    {
        self::$connection = '';
    }

    public function getConfig($key, $default = null): ?string
    {
        $connection = self::$connection;
        if (!$connection) {
            $connection = key(self::$databaseConfigs);
        }

        if (!isset(self::$config[$connection][$key])) {
            return $default;
        }

        return self::$config[$connection][$key];
    }

    public function getDatabaseConfig($key, $default = null): ?string
    {
        $connection = self::$connection;
        if (!$connection) {
            $connection = key(self::$databaseConfigs);
        }

        if (!isset(self::$databaseConfigs[$connection][$key])) {
            return $default;
        }

        return self::$databaseConfigs[$connection][$key];
    }

    public function addConfig($key, $value)
    {
        $connection = self::$connection;
        if (!$connection) {
            $connection = key(self::$databaseConfigs);
        }

        self::$config[$connection][$key] = $value;
    }

    public function getDatabaseConfigs(): array
    {
        return self::$databaseConfigs;
    }

    public function autoBackup(): bool
    {
        return $this->autoBackup;
    }

    public function autoRestore(): bool
    {
        return $this->autoRestore;
    }

    public function autoRemove(): bool
    {
        return $this->autoRemove;
    }

    public function getBackupPath(): string
    {
        return $this->backupPath;
    }

    public function keepClean(): bool
    {
        return $this->keepClean;
    }
}
