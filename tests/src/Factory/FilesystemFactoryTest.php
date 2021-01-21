<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem\Factory;

use Ixocreate\Filesystem\Exception\FilesystemNotFoundException;
use Ixocreate\Filesystem\Factory\FilesystemFactory;
use Ixocreate\Filesystem\FilesystemConfig;
use Ixocreate\Filesystem\FilesystemConfigurator;
use Ixocreate\Filesystem\FilesystemInterface;
use Ixocreate\Filesystem\Option\LocalOption;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Filesystem\Factory\FilesystemFactory
 */
class FilesystemFactoryTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        $filesystemConfigurator = new FilesystemConfigurator();
        $filesystemConfigurator->addStorage("test", new LocalOption(\getcwd()));
        $filesystemConfig = new FilesystemConfig($filesystemConfigurator);

        $this->container = $this->createMock(ServiceManagerInterface::class);
        $this->container->method("get")->willReturn($filesystemConfig);
    }

    public function testCreate()
    {
        $factory = new FilesystemFactory();
        $filesystem = $factory($this->container, "test", []);

        $this->assertInstanceOf(FilesystemInterface::class, $filesystem);

        $reflection = new \ReflectionClass($filesystem);
        $property = $reflection->getProperty("innerFilesystem");
        $property->setAccessible(true);

        $innerFilesystem = $property->getValue($filesystem);
        $reflection = new \ReflectionClass($innerFilesystem);
        $property = $reflection->getProperty('adapter');
        $property->setAccessible(true);

        $this->assertInstanceOf(LocalFilesystemAdapter::class, $property->getValue($innerFilesystem));
    }

    public function testException()
    {
        $this->expectException(FilesystemNotFoundException::class);

        $factory = new FilesystemFactory();
        $factory($this->container, "doenst_exist", []);
    }
}
