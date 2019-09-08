<?php

namespace Genesis\DBBackup\Extension\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use DBBackup\Config;
use DBBackup\DBBackupContext;

/**
 * ContextInitialiser class.
 */
class Initializer implements ContextInitializer
{
    /**
     * @var array
     */
    private $connection = [];

    /**
     * @var boolean
     */
    private $autoBackup = false;

    /**
     * @var boolean
     */
    private $autoRestore = false;

    /**
     * @var string
     */
    private $backupPath;

    /**
     * @param array $connection
     * @param array $dataModMapping
     */
    public function __construct(
        array $connection,
        string $backupPath,
        bool $autoBackup = false,
        bool $autoRestore = false
    ) {
        $this->connection = $connection;
        $this->backupPath = $backupPath;
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
    }

    /**
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof DBBackupContext) {
            $context::$config = new Config($this->connection, $this->backupPath, $this->autoBackup, $this->autoRestore);
        }
    }
}