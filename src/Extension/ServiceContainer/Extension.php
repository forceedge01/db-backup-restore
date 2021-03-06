<?php

namespace Genesis\DBBackup\Extension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Genesis\DBBackup\Extension\Initializer\Initializer;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Extension class.
 */
class Extension implements ExtensionInterface
{
    const CONTEXT_INITIALISER = 'genesis.dbbackup.context_initialiser';

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * Create definition object to handle in the context?
     */
    public function process(ContainerBuilder $container)
    {
        return;
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'GenesisDBBackupExtension';
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        return;
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('connections')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('host')->defaultNull()->end()
                            ->scalarNode('engine')->isRequired()->end()
                            ->scalarNode('dbname')->defaultNull()->end()
                            ->scalarNode('port')->defaultNull()->end()
                            ->scalarNode('username')->defaultNull()->end()
                            ->scalarNode('password')->defaultNull()->end()
                            ->scalarNode('schema')->defaultNull()->end()
                            ->scalarNode('prefix')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('autoBackup')
                    ->defaultNull(false)
                ->end()
                ->scalarNode('autoRestore')
                    ->defaultNull(false)
                ->end()
                ->scalarNode('autoRemove')
                    ->defaultNull(false)
                ->end()
                ->scalarNode('backupPath')
                    ->defaultNull(false)
                ->end()
                ->scalarNode('keepClean')
                    ->defaultNull(false)
                ->end()
            ->end()
        ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        if (empty($config['connections'])) {
            $config['connections'] = [];
        }
        $container->setParameter('genesis.dbbackup.config.connections', $config['connections']);

        if (! isset($config['backupPath'])) {
            throw new \Exception('Backup path is required.');
        }
        $container->setParameter('genesis.dbbackup.config.backupPath', $config['backupPath']);

        if (! isset($config['autoBackup'])) {
            $config['autoBackup'] = false;
        }
        $container->setParameter('genesis.dbbackup.config.autoBackup', $config['autoBackup']);

        if (! isset($config['autoRestore'])) {
            $config['autoRestore'] = false;
        }
        $container->setParameter('genesis.dbbackup.config.autoRestore', $config['autoRestore']);

        if (! isset($config['autoRemove'])) {
            $config['autoRemove'] = false;
        }
        $container->setParameter('genesis.dbbackup.config.autoRemove', $config['autoRemove']);

        if (! isset($config['keepClean'])) {
            $config['keepClean'] = false;
        }
        $container->setParameter('genesis.dbbackup.config.keepClean', $config['keepClean']);

        $definition = new Definition(Initializer::class, [
            '%genesis.dbbackup.config.connections%',
            '%genesis.dbbackup.config.backupPath%',
            '%genesis.dbbackup.config.autoBackup%',
            '%genesis.dbbackup.config.autoRestore%',
            '%genesis.dbbackup.config.autoRemove%',
            '%genesis.dbbackup.config.keepClean%',
        ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG);
        $container->setDefinition(self::CONTEXT_INITIALISER, $definition);
    }
}
