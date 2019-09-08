<?php

namespace DBBackup;

/**
 * Config class.
 */
class Config
{
    private $databaseConfig;

    private $backupConfig;

    public function __construct(array $databaseConfig = [], bool $autoBackup = false, bool $autoRestore = false)
    {
        $this->databaseConfig = $databaseConfig;
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
    }

    public function getDatabaseConfig(): array
    {
        return $this->databaseConfig;
    }

    public function autoBackup(): bool
    {
        return $this->autoBackup;
    }

    public function autoRestore(): bool
    {
        return $this->autoRestore;
    }
}
