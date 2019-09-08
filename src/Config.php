<?php

namespace DBBackup;

/**
 * Config class.
 */
class Config
{
    private $databaseConfig;

    private $backupConfig;

    public function __construct(array $databaseConfig, string $backupPath, bool $autoBackup = false, bool $autoRestore = false)
    {
        $this->databaseConfig = $databaseConfig;
        $this->backupPath = $backupPath;
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
    }

    public function getDatabaseConfig($key, $default = null): ?string
    {
        if (!isset($this->databaseConfig[$key])) {
            return $default;
        }

        return $this->databaseConfig[$key];
    }

    public function autoBackup(): bool
    {
        return $this->autoBackup;
    }

    public function autoRestore(): bool
    {
        return $this->autoRestore;
    }

    public function getBackupPath(): string
    {
        return $this->backupPath;
    }
}
