<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem\Option;

use Ixocreate\Filesystem\Adapter;
use Ixocreate\Filesystem\Option\LocalOption;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use League\Flysystem\Adapter\Local;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Filesystem\Option\LocalOption
 */
class LocalOptionTest extends TestCase
{
    public function testDefaults()
    {
        $localOption = new LocalOption(\getcwd());

        $this->assertSame(\getcwd(), $localOption->directory());
        $this->assertSame('disallow', $localOption->linkHandling());

        $this->assertSame(['public'  => 0644, 'private' => 0600], $localOption->filePermissions());
        $this->assertSame(['public'  => 0755, 'private' => 0700], $localOption->directoryPermissions());

        $this->assertTrue($localOption->isLockEx());
    }

    /**
     * @covers \Ixocreate\Filesystem\Option\LocalOption::withDisallowLinks
     */
    public function testWithDisallowLinks()
    {
        $localOption = (new LocalOption(\getcwd()))->withSkipLinks();
        $newOption = $localOption->withDisallowLinks();

        $this->assertSame('disallow', $newOption->linkHandling());
        $this->assertNotSame($newOption, $localOption);
    }

    /**
     * @covers \Ixocreate\Filesystem\Option\LocalOption::withSkipLinks
     */
    public function testWithSkipLinks()
    {
        $localOption = new LocalOption(\getcwd());
        $newOption = $localOption->withSkipLinks();

        $this->assertSame('skip', $newOption->linkHandling());
        $this->assertNotSame($newOption, $localOption);
    }

    /**
     * @covers \Ixocreate\Filesystem\Option\LocalOption::withDisableLockEx
     */
    public function testWithDisableLockEx()
    {
        $localOption = new LocalOption(\getcwd());
        $newOption = $localOption->withDisableLockEx();

        $this->assertFalse($newOption->isLockEx());
        $this->assertNotSame($newOption, $localOption);
    }

    /**
     * @covers \Ixocreate\Filesystem\Option\LocalOption::withEnableLockEx
     */
    public function testWithEnableLockEx()
    {
        $localOption = (new LocalOption(\getcwd()))->withDisableLockEx();
        $newOption = $localOption->withEnableLockEx();

        $this->assertTrue($newOption->isLockEx());
        $this->assertNotSame($newOption, $localOption);
    }

    public function testWithFilePermissions()
    {
        $localOption = new LocalOption(\getcwd());
        $newOption = $localOption->withFilePermissions(0400, 0500);

        $this->assertSame(['public' => 0400, 'private' => 0500], $newOption->filePermissions());
        $this->assertNotSame($newOption, $localOption);
    }

    public function testWithDirectoryPermissions()
    {
        $localOption = new LocalOption(\getcwd());
        $newOption = $localOption->withDirectoryPermissions(0400, 0500);

        $this->assertSame(['public' => 0400, 'private' => 0500], $newOption->directoryPermissions());
        $this->assertNotSame($newOption, $localOption);
    }

    /**
     * @covers \Ixocreate\Filesystem\Option\LocalOption::serialize
     * @covers \Ixocreate\Filesystem\Option\LocalOption::unserialize
     */
    public function testSerialize()
    {
        $localOption = new LocalOption(\getcwd());
        $localOption->withSkipLinks()
            ->withDisableLockEx()
            ->withFilePermissions(0400, 0500)
            ->withDirectoryPermissions(0400, 0500);

        /** @var LocalOption $newOption */
        $newOption = \unserialize(\serialize($localOption));

        $this->assertInstanceOf(LocalOption::class, $newOption);
        $this->assertSame($localOption->directory(), $newOption->directory());
        $this->assertSame($localOption->isLockEx(), $newOption->isLockEx());
        $this->assertSame($localOption->filePermissions(), $newOption->filePermissions());
        $this->assertSame($localOption->directoryPermissions(), $newOption->directoryPermissions());
    }

    public function testCreate()
    {
        $serviceManager = $this->createMock(ServiceManagerInterface::class);

        $localOption = new LocalOption(\getcwd());
        $localOption = $localOption->withSkipLinks()
            ->withDisableLockEx()
            ->withFilePermissions(0400, 0500)
            ->withDirectoryPermissions(0400, 0500);

        /** @var Adapter $adapter */
        $adapter = $localOption->create("test", $serviceManager);
        $this->assertInstanceOf(Local::class, $adapter->adapter());
        $reflection = new \ReflectionClass($adapter->adapter());
        $property = $reflection->getProperty("linkHandling");
        $property->setAccessible(true);
        $this->assertSame(Local::SKIP_LINKS, $property->getValue($adapter->adapter()));
        $property = $reflection->getProperty("writeFlags");
        $property->setAccessible(true);
        $this->assertSame(0, $property->getValue($adapter->adapter()));

        $localOption = new LocalOption(\getcwd());
        $localOption = $localOption->withDisallowLinks();
        /** @var Adapter $adapter */
        $adapter = $localOption->create("test", $serviceManager);
        $reflection = new \ReflectionClass($adapter->adapter());
        $property = $reflection->getProperty("linkHandling");
        $property->setAccessible(true);
        $this->assertSame(Local::DISALLOW_LINKS, $property->getValue($adapter->adapter()));
    }
}
