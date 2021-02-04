<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\Adapter;
use Ixocreate\Filesystem\Filesystem;
use Ixocreate\Filesystem\FilesystemInterface;
use Ixocreate\Filesystem\Settings;
use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\DirectoryListing;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Visibility;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Filesystem\Filesystem
 */
class FilesystemTest extends TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp(): void
    {
        $adapter = new Adapter(new class() implements FilesystemAdapter {
            public function fileExists(string $path): bool
            {
                return true;
            }

            public function write(string $path, string $contents, Config $config): void
            {
            }

            public function writeStream(string $path, $contents, Config $config): void
            {
            }

            public function read(string $path): string
            {
                return 'test';
            }

            public function readStream(string $path)
            {
                return \fopen('php://temp', 'rb');
            }

            public function delete(string $path): void
            {
            }

            public function deleteDirectory(string $path): void
            {
            }

            public function createDirectory(string $path, Config $config): void
            {
            }

            public function setVisibility(string $path, string $visibility): void
            {
            }

            public function visibility(string $path): FileAttributes
            {
                return new FileAttributes($path, null, Visibility::PRIVATE);
            }

            public function mimeType(string $path): FileAttributes
            {
                return new FileAttributes($path, null, null, null, 'image/png');
            }

            public function lastModified(string $path): FileAttributes
            {
                return new FileAttributes($path, null, null, 42);
            }

            public function fileSize(string $path): FileAttributes
            {
                return new FileAttributes($path, 42);
            }

            public function listContents(string $path, bool $deep): iterable
            {
                return new \ArrayIterator([
                    new FileAttributes('file1', 123, Visibility::PUBLIC, 42),
                    new DirectoryAttributes('dir1', Visibility::PUBLIC, 43),
                    new FileAttributes('dir1/file1', 234, Visibility::PUBLIC, 44),
                    new FileAttributes('dir2/file2', 345, Visibility::PRIVATE, 45),
                ]);
            }

            public function move(string $source, string $destination, Config $config): void
            {
            }

            public function copy(string $source, string $destination, Config $contestSyncWithDoFlagsfig): void
            {
            }
        });

        $this->filesystem = new Filesystem($adapter, new Settings(['disable_asserts' => true]));
    }

    public function testHas()
    {
        $this->assertTrue($this->filesystem->has("test"));
    }

    public function testRead()
    {
        $this->assertSame("test", $this->filesystem->read("test"));
    }

    public function testReadStream()
    {
        $this->assertIsResource($this->filesystem->readStream("test"));
    }

    public function testListContents()
    {
        $this->assertEquals(new DirectoryListing(new \ArrayIterator([
            new FileAttributes('file1', 123, Visibility::PUBLIC, 42),
            new DirectoryAttributes('dir1', Visibility::PUBLIC, 43),
            new FileAttributes('dir1/file1', 234, Visibility::PUBLIC, 44),
            new FileAttributes('dir2/file2', 345, Visibility::PRIVATE, 45),
        ])), $this->filesystem->listContents('', true));
    }

    public function testGetSize()
    {
        $this->assertSame(42, $this->filesystem->getSize('test'));
    }

    public function testGetMimetype()
    {
        $this->assertSame('image/png', $this->filesystem->getMimetype('test'));
    }

    public function testGetTimestamp()
    {
        $this->assertSame(42, $this->filesystem->getTimestamp('test'));
    }

    public function testGetVisibility()
    {
        $this->assertSame('private', $this->filesystem->getVisibility('test'));
    }

    public function testWrite()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('write')->with(
            $this->equalTo('test'),
            $this->equalTo('content')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->write('test', 'content');
    }

    public function testWriteStream()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('writeStream')->with(
            $this->equalTo('test'),
            $this->callback(function ($value) {
                return \is_resource($value);
            }),
            $this->anything()
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->writeStream('test', \fopen('php://temp', 'rb'));
    }

    public function testRename()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('move')->with(
            $this->equalTo('old'),
            $this->equalTo('new')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->rename('old', 'new');
    }

    public function testCopy()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('copy')->with(
            $this->equalTo('old'),
            $this->equalTo('new')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->copy('old', 'new');
    }

    public function testDelete()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('delete')->with(
            $this->equalTo('test')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->delete('test');
    }

    public function testDeleteDir()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('deleteDirectory')->with(
            $this->equalTo('test')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->deleteDir('test');
    }

    public function testCreateDir()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('createDirectory')->with(
            $this->equalTo('test')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->createDir('test');
    }

    public function testSetVisibility()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('setVisibility')->with(
            $this->equalTo('test'),
            $this->equalTo('private')
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter));
        $filesystem->setVisibility('test', 'private');
    }

    public function testConfig()
    {
        $mockAdapter = $this->createMock(FilesystemAdapter::class);
        $mockAdapter->expects($this->once())->method('write')->with(
            $this->anything(),
            $this->anything(),
            $this->equalTo(new Config(['disable_asserts' => true, 'test1' => true, 'check' => false]))
        );

        $filesystem = new Filesystem(new Adapter($mockAdapter), new Settings(['disable_asserts' => true, 'test1' => false]));
        $filesystem->write("check", 'content', new Settings(['check' => false, 'test1' => true]));
    }

    public function testSyncWithRoot()
    {
        $destinationFiles = new DirectoryListing(new \ArrayIterator([
            new DirectoryAttributes('d/dir1'),
            new FileAttributes('d/dir1/file1'),
            new FileAttributes('d/dir1/file2'),
            new DirectoryAttributes('d/dir2'),
            new FileAttributes('d/dir2/file1'),
            new FileAttributes('d/dir2/file2'),
        ]));

        $sourceFiles = new DirectoryListing(new \ArrayIterator([
            new DirectoryAttributes('dir1'),
            new FileAttributes('dir1/file1'),
            new FileAttributes('dir1/file2'),
            new DirectoryAttributes('dir3'),
            new FileAttributes('dir3/file1'),
            new FileAttributes('dir3/file2'),
        ]));

        $sourceFilesystem = $this->createMock(FilesystemInterface::class);
        $sourceFilesystem->method("listContents")->willReturn($sourceFiles);
        $sourceFilesystem->method("readStream")->willReturn(\fopen("php://temp", "r"));

        $flysystemAdapter = $this->createMock(FilesystemAdapter::class);
        $flysystemAdapter->method("listContents")->willReturn($destinationFiles);
        $flysystemAdapter->method("deleteDirectory");
        $flysystemAdapter->method("delete");
        $flysystemAdapter->method("writeStream");
        $flysystemAdapter->method("createDirectory");
        $filesystem = new Filesystem(new Adapter($flysystemAdapter), new Settings(['disable_asserts' => true]));

        $result = $filesystem->syncFrom(
            $sourceFilesystem,
            new Settings([
                'sourceRoot' => 's',
                'destinationRoot' => 'd',
            ])
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey('update', $result);
        $this->assertArrayHasKey('create', $result);
        $this->assertArrayHasKey('delete', $result);

        $this->assertSame([
            'd/dir1/file1',
            'd/dir1/file2',
        ], $result['update']);
        $this->assertSame([
            'd/dir3',
            'd/dir3/file1',
            'd/dir3/file2',
        ], $result['create']);
        $this->assertSame([
            'd/dir2/file2',
            'd/dir2/file1',
            'd/dir2',
        ], $result['delete']);
    }

    public function testSyncWithoutRoot()
    {
        $destinationFiles = new DirectoryListing(new \ArrayIterator([
            new DirectoryAttributes('dir1'),
            new FileAttributes('dir1/file1'),
            new FileAttributes('dir1/file2'),
            new DirectoryAttributes('dir2'),
            new FileAttributes('dir2/file1'),
            new FileAttributes('dir2/file2'),
        ]));

        $sourceFiles = new DirectoryListing(new \ArrayIterator([
            new DirectoryAttributes('dir1'),
            new FileAttributes('dir1/file1'),
            new FileAttributes('dir1/file2'),
            new DirectoryAttributes('dir3'),
            new FileAttributes('dir3/file1'),
            new FileAttributes('dir3/file2'),
        ]));

        $sourceFilesystem = $this->createMock(FilesystemInterface::class);
        $sourceFilesystem->method("listContents")->willReturn($sourceFiles);
        $sourceFilesystem->method("readStream")->willReturn(\fopen("php://temp", "r"));

        $flysystemAdapter = $this->createMock(FilesystemAdapter::class);
        $flysystemAdapter->method("listContents")->willReturn($destinationFiles);
        $flysystemAdapter->method("deleteDirectory");
        $flysystemAdapter->method("delete");
        $flysystemAdapter->method("writeStream");
        $flysystemAdapter->method("createDirectory");
        $filesystem = new Filesystem(new Adapter($flysystemAdapter), new Settings(['disable_asserts' => true]));

        $result = $filesystem->syncFrom($sourceFilesystem);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('update', $result);
        $this->assertArrayHasKey('create', $result);
        $this->assertArrayHasKey('delete', $result);

        $this->assertSame([
            'dir1/file1',
            'dir1/file2',
        ], $result['update']);
        $this->assertSame([
            'dir3',
            'dir3/file1',
            'dir3/file2',
        ], $result['create']);
        $this->assertSame([
            'dir2/file2',
            'dir2/file1',
            'dir2',
        ], $result['delete']);
    }

    public function testSyncWithDoFlags()
    {
        $destinationFiles = new DirectoryListing(new \ArrayIterator([
            new FileAttributes('file1'),
            new FileAttributes('file2'),
        ]));

        $sourceFiles = new DirectoryListing(new \ArrayIterator([
            new FileAttributes('file1'),
            new FileAttributes('file3'),
        ]));

        $sourceFilesystem = $this->createMock(FilesystemInterface::class);
        $sourceFilesystem->method("listContents")->willReturn($sourceFiles);
        $sourceFilesystem->method("readStream")->willReturn(\fopen("php://temp", "r"));

        $flysystemAdapter = $this->createMock(FilesystemAdapter::class);
        $flysystemAdapter->method("listContents")->willReturn($destinationFiles);
        $flysystemAdapter->method("deleteDirectory");
        $flysystemAdapter->method("delete");
        $flysystemAdapter->method("writeStream");
        $flysystemAdapter->method("createDirectory");
        $filesystem = new Filesystem(new Adapter($flysystemAdapter), new Settings(['disable_asserts' => true]));

        $result = $filesystem->syncFrom(
            $sourceFilesystem,
            new Settings(['doUpdate' => false])
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey('update', $result);
        $this->assertArrayHasKey('create', $result);
        $this->assertArrayHasKey('delete', $result);

        $this->assertEmpty($result['update']);
        $this->assertSame([
            'file3',
        ], $result['create']);
        $this->assertSame([
            'file2',
        ], $result['delete']);

        $result = $filesystem->syncFrom(
            $sourceFilesystem,
            new Settings(['doDelete' => false])
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey('update', $result);
        $this->assertArrayHasKey('create', $result);
        $this->assertArrayHasKey('delete', $result);

        $this->assertSame([
            'file1',
        ], $result['update']);
        $this->assertSame([
            'file3',
        ], $result['create']);
        $this->assertEmpty($result['delete']);

        $result = $filesystem->syncFrom(
            $sourceFilesystem,
            new Settings(['doCreate' => false])
        );
        $this->assertIsArray($result);
        $this->assertArrayHasKey('update', $result);
        $this->assertArrayHasKey('create', $result);
        $this->assertArrayHasKey('delete', $result);

        $this->assertSame([
            'file1',
        ], $result['update']);
        $this->assertEmpty($result['create']);
        $this->assertSame([
            'file2',
        ], $result['delete']);
    }
}
