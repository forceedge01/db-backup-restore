<?php

/**
 * Handler interface.
 */
interface HandlerInterface
{
    /**
     * A shell executable command to backup the database.
     *
     * @param Config $config
     *
     * @return string
     */
    public static function backupCommand(Config $config): string;

    /**
     * A shell executable command to restore the database from file.
     *
     * @param Config $config The config to create the command.
     *
     * @return string
     */
    public static function restoreCommand(Config $config): string;

    /**
     * A shell executable command to drop the database.
     *
     * @param Config $config
     *
     * @return string
     */
    public static function dropDatabaseCommand(Config $config): string;

    /**
     * A shell executable command to create the database only.
     *
     * @param Config $config
     *
     * @return string
     */
    public static function createDatabaseCommand(Config $config): string;
}
