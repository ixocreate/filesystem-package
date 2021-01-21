<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\FilesystemConfig;
use Ixocreate\Filesystem\FilesystemConfigurator;
use Ixocreate\Filesystem\OptionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Filesystem\FilesystemConfig
 */
class FilesystemConfigTest extends TestCase
{
    /**
     * @var array
     */
    private $storage;

    /**
     * @var FilesystemConfig
     */
    private $filesystemConfig;

    public function setUp(): void
    {
        $option = $this->createMock(OptionInterface::class);
        $option->method('serialize')->willReturn(\serialize(""));
        $option->method("unserialize")->willReturn(null);

        $this->storage = [
            'test1' => $option,
            'test2' => $option,
        ];

        $configurator = new FilesystemConfigurator();
        foreach ($this->storage as $name => $option) {
            $configurator->addStorage($name, $option);
        }

        $this->filesystemConfig = new FilesystemConfig($configurator);
    }

    public function testConfig()
    {
        $this->runTests($this->filesystemConfig);
    }

    public function testSerialize()
    {
        $this->runTests(\unserialize(\serialize($this->filesystemConfig)));
    }

    private function runTests(FilesystemConfig $filesystemConfig)
    {
        foreach ($filesystemConfig->names() as $name) {
            $this->assertArrayHasKey($name, $this->storage);
        }

        foreach (\array_keys($this->storage) as $name) {
            $this->assertTrue($filesystemConfig->has($name));
        }
        $this->assertFalse($filesystemConfig->has("doesnt_exist"));

        foreach ($this->storage as $name => $option) {
            $this->assertInstanceOf(OptionInterface::class, $filesystemConfig->get($name));
        }
    }
}
