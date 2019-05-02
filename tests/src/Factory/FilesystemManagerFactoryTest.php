<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem\Factory;

use Ixocreate\Filesystem\Factory\FilesystemManagerFactory;
use Ixocreate\Filesystem\FilesystemConfig;
use Ixocreate\Filesystem\FilesystemConfigurator;
use Ixocreate\Filesystem\FilesystemManager;
use Ixocreate\Filesystem\OptionInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class FilesystemManagerFactoryTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\Factory\FilesystemManagerFactory
     * @covers \Ixocreate\Filesystem\FilesystemManager
     */
    public function testFactory()
    {
        $storage = [
            'test1' => $this->createMock(OptionInterface::class),
            'test2' => $this->createMock(OptionInterface::class),
        ];
        $configurator = new FilesystemConfigurator();
        foreach ($storage as $name => $option) {
            $configurator->addStorage($name, $option);
        }

        $filesystemConfig = new FilesystemConfig($configurator);

        $container = $this->createMock(ServiceManagerInterface::class);
        $container->method("get")->willReturn($filesystemConfig);

        $factory = new FilesystemManagerFactory();

        $manager = $factory->__invoke($container, FilesystemManager::class, []);
        foreach ($storage as $name => $value) {
            $this->assertTrue($manager->has($name));
        }

        $this->assertFalse($manager->has("doesnt_exist"));
    }
}
