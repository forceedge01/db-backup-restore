<?php

namespace Genesis\DBBackup\Extension\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Genesis\DBBackup\Config;
use Genesis\DBBackup\DBBackupContext;

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
     * @var boolean
     */
    private $autoRemove = false;

    /**
     * @var string
     */
    private $backupPath;

    /**
     * @param array $connection
     * @param array $dataModMapping
     */
    public function __construct(
        array $connections,
        string $backupPath,
        bool $autoBackup = false,
        bool $autoRestore = false,
        bool $autoRemove = false
    ) {
        $this->connections = $connections;
        $this->backupPath = $backupPath;
        $this->autoBackup = $autoBackup;
        $this->autoRestore = $autoRestore;
        $this->autoRemove = $autoRemove;
    }

    /**
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof DBBackupContext) {
            $context::$config = new Config(
                $this->connections,
                $this->backupPath,
                $this->autoBackup,
                $this->autoRestore,
                $this->autoRemove
            );
        }
    }
}
