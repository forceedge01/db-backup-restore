<?php

namespace Genesis\DBBackup;

/**
 * Config class.
 */
class Config
{
    private $databaseConfig;

    public function __construct(
        array $databaseConfig,
        string $backupPath,
        bool $autoBackup = false,
        bool $autoRestore = false,
        bool $autoRemove = false
    ) {
        $this->databaseConfig = $databaseConfig;
        $this->backupPath = $backupPath;
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
        $this->autoRemove = $autoRemove;
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

    public function autoRemove(): bool
    {
        return $this->autoRemove;
    }

    public function getBackupPath(): string
    {
        return $this->backupPath;
    }
}
