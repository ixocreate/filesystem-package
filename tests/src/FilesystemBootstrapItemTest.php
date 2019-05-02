<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\FilesystemBootstrapItem;
use Ixocreate\Filesystem\FilesystemConfigurator;
use PHPUnit\Framework\TestCase;

class FilesystemBootstrapItemTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\FilesystemBootstrapItem
     */
    public function testBootstrapItem()
    {
        $item = new FilesystemBootstrapItem();

        $this->assertSame('filesystem.php', $item->getFileName());
        $this->assertSame('filesystem', $item->getVariableName());
        $this->assertInstanceOf(FilesystemConfigurator::class, $item->getConfigurator());
    }
}
