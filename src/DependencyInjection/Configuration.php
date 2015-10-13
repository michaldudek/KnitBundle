<?php
namespace Knit\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Knit Bundle configuration.
 *
 * @package    KnitBundle
 * @subpackage DependencyInjection
 * @author     Michał Pałys-Dudek <michal@michaldudek.pl>
 * @copyright  2015 Michał Pałys-Dudek
 * @license    https://github.com/michaldudek/KnitBundle/blob/master/LICENSE.md MIT License
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('knit');

        // @codingStandardsIgnoreStart
        $rootNode
            ->children()
                ->scalarNode('store')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Name of a service that will be used as the default store.')
                ->end()
                ->scalarNode('data_mapper')
                    ->defaultValue('knit.data_mapper.array_serializer')
                    ->info('Name of a service that will be used as the default data mapper.')
                ->end()
                ->scalarNode('event_dispatcher')
                    ->defaultValue('event_dispatcher')
                    ->info('Name of a service that will be used as an event dispatcher.')
                ->end()
            ->end();
        // @codingStandardsIgnoreEnd

        return $treeBuilder;
    }
}
