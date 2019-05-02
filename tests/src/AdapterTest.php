<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\Adapter;
use League\Flysystem\AdapterInterface;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\Adapter
     */
    public function testAdapter()
    {
        $innerAdapter = $this->createMock(AdapterInterface::class);
        $adapter = new Adapter($innerAdapter);

        $this->assertSame($innerAdapter, $adapter->adapter());
    }
}
