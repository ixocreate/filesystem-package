<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\FilesystemInterface;
use Ixocreate\Filesystem\Sync;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\DirectoryListing;
use League\Flysystem\FileAttributes;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Filesystem\Sync
 */
class SyncTest extends TestCase
{
    /**
     * @dataProvider fileProvider
     * @param mixed $sourceFiles
     * @param mixed $destinationFiles
     * @param mixed $sourceRoot
     * @param mixed $destRoot
     */
    public function testSync($sourceFiles, $destinationFiles, $sourceRoot, $destRoot)
    {
        $filesystemSource = $this->createMock(FilesystemInterface::class);
        $filesystemSource->method("listContents")->willReturn($sourceFiles);

        $filesystemDestination = $this->createMock(FilesystemInterface::class);
        $filesystemDestination->method("listContents")->willReturn($destinationFiles);

        $diff = (new Sync())->diff(
            $filesystemSource,
            $filesystemDestination,
            $sourceRoot,
            $destRoot
        );

        $this->assertArrayHasKey('update', $diff);
        $this->assertArrayHasKey('create', $diff);
        $this->assertArrayHasKey('delete', $diff);

        $diffUpdate = [];
        $diffCreate = [];
        $diffDelete = [];

        foreach ($sourceFiles as $sourceItem) {
            $sourcePath = $sourceItem['path'];
            if (!empty($sourceRoot) && \mb_substr($sourcePath, 0, \mb_strlen($sourceRoot) + 1) === $sourceRoot . '/') {
                $sourcePath = \mb_substr($sourcePath, \mb_strlen($sourceRoot) + 1);
            }

            foreach ($destinationFiles as $destinationItem) {
                $destinationItemPath = $destinationItem['path'];
                if (!empty($destRoot) && \mb_substr($destinationItemPath, 0, \mb_strlen($destRoot) + 1) === $destRoot . '/') {
                    $destinationItemPath = \mb_substr($destinationItemPath, \mb_strlen($destRoot) + 1);
                }


                if ($destinationItem['type'] === $sourceItem['type'] && $destinationItemPath === $sourcePath) {
                    $diffUpdate[] = $sourceItem;
                    continue 2;
                }
            }

            $diffCreate[] = $sourceItem;
        }

        foreach ($destinationFiles as $destinationItem) {
            $destinationItemPath = $destinationItem['path'];
            if (!empty($destRoot) && \mb_substr($destinationItemPath, 0, \mb_strlen($destRoot) + 1) === $destRoot . '/') {
                $destinationItemPath = \mb_substr($destinationItemPath, \mb_strlen($destRoot) + 1);
            }

            foreach ($sourceFiles as $sourceItem) {
                $sourcePath = $sourceItem['path'];
                if (!empty($sourceRoot) && \mb_substr($sourcePath, 0, \mb_strlen($sourceRoot) + 1) === $sourceRoot . '/') {
                    $sourcePath = \mb_substr($sourcePath, \mb_strlen($sourceRoot) + 1);
                }

                if ($destinationItem['type'] === $sourceItem['type'] && $destinationItemPath === $sourcePath) {
                    continue 2;
                }
            }

            $diffDelete[] = $destinationItem;
        }

        $this->assertSame($diffUpdate, $diff['update']);
        $this->assertSame($diffCreate, $diff['create']);
        $this->assertSame(\array_reverse($diffDelete), $diff['delete']);
    }

    public function fileProvider()
    {
        return [
            [
                new DirectoryListing(new \ArrayIterator([
                    new FileAttributes('dir1'),
                    new FileAttributes('dir1/file1'),
                    new FileAttributes('dir1/file3'),
                    new FileAttributes('dir2'),
                    new FileAttributes('dir2/file1'),
                    new FileAttributes('dir2/file2'),
                    new FileAttributes('dir3'),
                    new FileAttributes('dir3/file1'),
                    new FileAttributes('dir3/file2'),
                    new FileAttributes('dir3/file3'),
                    new FileAttributes('dir3/file4'),
                ])),
                new DirectoryListing(new \ArrayIterator([
                    new FileAttributes('dir1'),
                    new FileAttributes('dir1/file1'),
                    new FileAttributes('dir1/file2'),
                    new FileAttributes('dir1/file3'),
                    new FileAttributes('dir1/file4'),
                    new FileAttributes('dir2'),
                    new FileAttributes('dir2/file1'),
                    new FileAttributes('dir2/file2'),
                    new FileAttributes('dir3'),
                    new FileAttributes('dir3/file1'),
                    new FileAttributes('dir3/file2'),
                    new FileAttributes('dir3/file3'),
                ])),
                'sourceRoot',
                'destRoot',
            ],
            [
                new DirectoryListing(new \ArrayIterator([
                    new DirectoryAttributes('sourceRoot/dir1'),
                    new FileAttributes('sourceRoot/dir1/file1'),
                ])),
                new DirectoryListing(new \ArrayIterator([
                    new DirectoryAttributes('destRoot/dir1'),
                    new FileAttributes('destRoot/dir1/file1'),
                ])),
                'sourceRoot',
                'destRoot',
            ],
        ];
    }
}
