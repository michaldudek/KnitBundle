<?php
namespace Knit\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Knit\Knit;

use Knit\Bundle\DependencyInjection\Configuration;

/**
 * Knit Bundle Symfony Dependency Injection Container extension.
 *
 * @package    KnitBundle
 * @subpackage DependencyInjection
 * @author     Michał Pałys-Dudek <michal@michaldudek.pl>
 * @copyright  2015 Michał Pałys-Dudek
 * @license    https://github.com/michaldudek/KnitBundle/blob/master/LICENSE.md MIT License
 */
class KnitExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @param array            $configs   Configs.
     * @param ContainerBuilder $container Container builder.
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        // register Knit service
        $container->register('knit', Knit::class)
            ->addArgument(new Reference($config['store']))
            ->addArgument(new Reference($config['data_mapper']))
            ->addArgument(new Reference($config['event_dispatcher']));

        // and then register everything else
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ .'/../Resources/config'));
        $loader->load('services.yml');
    }
}
