<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Application\Configurator\ConfiguratorRegistryInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Filesystem\ConfigProvider;
use Ixocreate\Filesystem\Package;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\Package
     */
    public function testPackage()
    {
        $configuratorRegistry = $this->getMockBuilder(ConfiguratorRegistryInterface::class)->getMock();
        $serviceRegistry = $this->getMockBuilder(ServiceRegistryInterface::class)->getMock();
        $serviceManager = $this->getMockBuilder(ServiceManagerInterface::class)->getMock();

        $package = new Package();
        $package->configure($configuratorRegistry);
        $package->addServices($serviceRegistry);
        $package->boot($serviceManager);

        $this->assertNull($package->getBootstrapItems());
        $this->assertNull($package->getConfigDirectory());
        $this->assertSame([
            ConfigProvider::class,
        ], $package->getConfigProvider());
        $this->assertNull($package->getDependencies());
        $this->assertDirectoryExists($package->getBootstrapDirectory());
    }
}
