<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Filesystem\FilesystemConfig;
use Ixocreate\Filesystem\FilesystemConfigurator;
use Ixocreate\Filesystem\OptionInterface;
use PHPUnit\Framework\TestCase;

class FilesystemConfiguratorTest extends TestCase
{
    public function testStorage()
    {
        $storage = [
            'test1' => $this->createMock(OptionInterface::class),
            'test2' => $this->createMock(OptionInterface::class),
        ];

        $configurator = new FilesystemConfigurator();
        foreach ($storage as $name => $option) {
            $configurator->addStorage($name, $option);
        }

        $check = $configurator->storagePool();
        foreach ($check as $name => $option) {
            $this->assertArrayHasKey($name, $storage);
            $this->assertSame($option, $storage[$name]);
        }
    }

    /**
     * @covers \Ixocreate\Filesystem\FilesystemConfigurator::registerService
     */
    public function testRegisterService()
    {
        $collector = [];
        $serviceRegistry = $this->createMock(ServiceRegistryInterface::class);
        $serviceRegistry->method('add')->willReturnCallback(function ($name, $object) use (&$collector) {
            $collector[$name] = $object;
        });

        $configurator = new FilesystemConfigurator();
        $configurator->registerService($serviceRegistry);

        $this->assertArrayHasKey(FilesystemConfig::class, $collector);
        $this->assertInstanceOf(FilesystemConfig::class, $collector[FilesystemConfig::class]);
    }
}
