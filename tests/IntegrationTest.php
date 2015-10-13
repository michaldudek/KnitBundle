<?php
namespace Knit\Bundle\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Knit\Bundle\DependencyInjection\Configuration;
use Knit\Bundle\DependencyInjection\KnitExtension;

use Knit\Knit;

/**
 * Integration test that checks the configuration, if the container isn't broken
 * and are expected services are there.
 *
 * @package    KnitBundle
 * @subpackage Tests
 * @author     Michał Pałys-Dudek <michal@michaldudek.pl>
 * @copyright  2015 Michał Pałys-Dudek
 * @license    https://github.com/michaldudek/KnitBundle/blob/master/LICENSE.md MIT License
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if the extension loads without problems.
     *
     * @return array
     */
    public function testExtensionLoading()
    {
        $extension = new KnitExtension();

        $parameters = new ParameterBag(
            [
                'kernel.debug' => false,
                'kernel.cache_dir' => sys_get_temp_dir()
            ]
        );
        $container = new ContainerBuilder($parameters);
        $container->registerExtension($extension);

        $container->register('knit.store.memory', Fixtures\MemoryStore::class);
        $container->register('event_dispatcher', EventDispatcher::class);

        $baseConfig = ['store' => 'knit.store.mongodb'];
        $prodConfig = ['store' => 'knit.store.memory'];
        $extension->load([$baseConfig, $prodConfig], $container);

        $container->compile();
        return $container;
    }

    /**
     * Tests if all services are properly registered.
     *
     * @param ContainerInterface $container Container.
     *
     * @depends testExtensionLoading
     */
    public function testServices(ContainerInterface $container)
    {
        $knit = $container->get('knit');
        $this->assertInstanceOf(Knit::class, $knit);
    }
}
