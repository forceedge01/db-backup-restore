<?php

namespace Genesis\DBBackup;

/**
 * Config class.
 */
class Config
{
    private $databaseConfigs;

    private static $connection;

    public function __construct(
        array $databaseConfigs,
        string $backupPath,
        bool $autoBackup = false,
        bool $autoRestore = false,
        bool $autoRemove = false
    ) {
        $this->databaseConfigs = $databaseConfigs;
        $this->backupPath = realpath($backupPath);
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
        $this->autoRemove = $autoRemove;

        if (! $this->backupPath) {
            throw new \Exception("Path $backupPath not found.");
        }
    }

    public function setActiveConfig($connection)
    {
        self::$connection = '';
    }

    public function getDatabaseConfig($key, $default = null): ?string
    {
        $connection = self::$connection;
        if (!$connection) {
            $connection = key($this->databaseConfigs);
        }

        if (!isset($this->databaseConfigs[$connection][$key])) {
            return $default;
        }

        return $this->databaseConfigs[$connection][$key];
    }

    public function addDatabaseConfig($key, $value)
    {
        $connection = self::$connection;
        if (!$connection) {
            $connection = key($this->databaseConfigs);
        }

        $this->databaseConfigs[$connection][$key] = $value;
    }

    public function getDatabaseConfigs(): array
    {
        return $this->databaseConfigs;
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
}
