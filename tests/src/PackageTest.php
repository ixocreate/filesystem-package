<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\FilesystemBootstrapItem;
use Ixocreate\Filesystem\Package;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    /**
     * @covers \Ixocreate\Filesystem\Package
     */
    public function testPackage()
    {
        $package = new Package();

        $this->assertSame([FilesystemBootstrapItem::class], $package->getBootstrapItems());
        $this->assertEmpty($package->getDependencies());
        $this->assertDirectoryExists($package->getBootstrapDirectory());
    }
}
