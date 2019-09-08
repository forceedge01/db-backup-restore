<?php

namespace Genesis\DBBackup\Extension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Genesis\SQLExtensionWrapper\Extension\Initializer\Initializer;
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
                ->arrayNode('connection')
                    ->isRequired()
                    ->children()
                        ->scalarNode('host')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('engine')
                            ->isRequired()
                        ->end()
                        ->scalarNode('dbname')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('port')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('username')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('password')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('schema')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('prefix')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('autoBackup')
                    ->defaultNull(false)
                ->end()
                ->scalarNode('autoRestore')
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
        if (! isset($config['connection'])) {
            $config['connection'] = [];
        }
        $container->setParameter('genesis.dbbackup.config.connection', $config['connection']);

        if (! isset($config['autoBackup'])) {
            $config['autoBackup'] = false;
        }
        $container->setParameter('genesis.dbbackup.config.autoBackup', $config['autoBackup']);

        if (! isset($config['autoRestore'])) {
            $config['autoRestore'] = false;
        }
        $container->setParameter('genesis.dbbackup.config.autoRestore', $config['autoBackup']);

        $definition = new Definition(Initializer::class, [
            '%genesis.dbbackup.config.connection%',
            '%genesis.dbbackup.config.autoBackup%',
            '%genesis.dbbackup.config.autoRestore%',
        ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG);
        $container->setDefinition(self::CONTEXT_INITIALISER, $definition);
    }
}
