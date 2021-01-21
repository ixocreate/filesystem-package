<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\Adapter;
use League\Flysystem\FilesystemAdapter;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\Adapter
     */
    public function testAdapter()
    {
        $innerAdapter = $this->createMock(FilesystemAdapter::class);
        $adapter = new Adapter($innerAdapter);

        $this->assertSame($innerAdapter, $adapter->adapter());
    }
}
