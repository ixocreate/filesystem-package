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
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
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

    public function setUp()
    {
        $adapter = new Adapter(new class() implements AdapterInterface {
            /**
             * Write a new file.
             *
             * @param string $path
             * @param string $contents
             * @param Config $config Config object
             *
             * @return array|false false on failure file meta data on success
             */
            public function write($path, $contents, Config $config)
            {
                if ($path === "check" && $config->get("check", false) === true) {
                    return false;
                }
                return ['path' => $path];
            }

            /**
             * Write a new file using a stream.
             *
             * @param string $path
             * @param resource $resource
             * @param Config $config Config object
             *
             * @return array|false false on failure file meta data on success
             */
            public function writeStream($path, $resource, Config $config)
            {
                return ['path' => $path];
            }

            /**
             * Update a file.
             *
             * @param string $path
             * @param string $contents
             * @param Config $config Config object
             *
             * @return array|false false on failure file meta data on success
             */
            public function update($path, $contents, Config $config)
            {
                return ['path' => $path];
            }

            /**
             * Update a file using a stream.
             *
             * @param string $path
             * @param resource $resource
             * @param Config $config Config object
             *
             * @return array|false false on failure file meta data on success
             */
            public function updateStream($path, $resource, Config $config)
            {
                return ['path' => $path];
            }

            /**
             * Rename a file.
             *
             * @param string $path
             * @param string $newpath
             *
             * @return bool
             */
            public function rename($path, $newpath)
            {
                return true;
            }

            /**
             * Copy a file.
             *
             * @param string $path
             * @param string $newpath
             *
             * @return bool
             */
            public function copy($path, $newpath)
            {
                return true;
            }

            /**
             * Delete a file.
             *
             * @param string $path
             *
             * @return bool
             */
            public function delete($path)
            {
                return true;
            }

            /**
             * Delete a directory.
             *
             * @param string $dirname
             *
             * @return bool
             */
            public function deleteDir($dirname)
            {
                return true;
            }

            /**
             * Create a directory.
             *
             * @param string $dirname directory name
             * @param Config $config
             *
             * @return array|false
             */
            public function createDir($dirname, Config $config)
            {
                return ['path' => $dirname, 'type' => 'dir'];
            }

            /**
             * Set the visibility for a file.
             *
             * @param string $path
             * @param string $visibility
             *
             * @return array|false file meta data
             */
            public function setVisibility($path, $visibility)
            {
                return \compact('path', 'visibility');
            }

            /**
             * Check whether a file exists.
             *
             * @param string $path
             *
             * @return array|bool|null
             */
            public function has($path)
            {
                return true;
            }

            /**
             * Read a file.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function read($path)
            {
                return ['type' => 'file', 'path' => $path, 'contents' => "test"];
            }

            /**
             * Read a file as a stream.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function readStream($path)
            {
                return ['type' => 'file', 'path' => $path, 'stream' => \fopen('php://temp', 'rb')];
            }

            /**
             * List contents of a directory.
             *
             * @param string $directory
             * @param bool $recursive
             *
             * @return array
             */
            public function listContents($directory = '', $recursive = false)
            {
                return [
                    [
                        'type' => 'dir',
                        'path' => 'dir1',
                    ],
                    [
                        'type' => 'file',
                        'path' => 'dir1/file1',
                    ],
                    [
                        'type' => 'file',
                        'path' => 'dir1/file2',
                    ],
                ];
            }

            /**
             * Get all the meta data of a file or directory.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function getMetadata($path)
            {
                return ['test' => 'test'];
            }

            /**
             * Get the size of a file.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function getSize($path)
            {
                return ['size' => 42];
            }

            /**
             * Get the mimetype of a file.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function getMimetype($path)
            {
                return ['mimetype' => 'image/png'];
            }

            /**
             * Get the last modified time of a file as a timestamp.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function getTimestamp($path)
            {
                return ['timestamp' => '42'];
            }

            /**
             * Get the visibility of a file.
             *
             * @param string $path
             *
             * @return array|false
             */
            public function getVisibility($path)
            {
                return ['visibility' => 'private'];
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
        $this->assertSame([
            [
                'type' => 'dir',
                'path' => 'dir1',
                'dirname' => '',
                'basename' => 'dir1',
                'filename' => 'dir1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file1',
                'dirname' => 'dir1',
                'basename' => 'file1',
                'filename' => 'file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file2',
                'dirname' => 'dir1',
                'basename' => 'file2',
                'filename' => 'file2',
            ],
        ], $this->filesystem->listContents("", true));
    }

    public function testGetMetadata()
    {
        $this->assertSame(['test' => 'test'], $this->filesystem->getMetadata("test"));
    }

    public function testGetSize()
    {
        $this->assertSame(42, $this->filesystem->getSize("test"));
    }

    public function testGetMimetype()
    {
        $this->assertSame('image/png', $this->filesystem->getMimetype("test"));
    }

    public function testGetTimestamp()
    {
        $this->assertSame('42', $this->filesystem->getTimestamp("test"));
    }

    public function testGetVisibility()
    {
        $this->assertSame('private', $this->filesystem->getVisibility("test"));
    }

    public function testWrite()
    {
        $this->assertTrue($this->filesystem->write("test", 'content'));
    }

    public function testWriteStream()
    {
        $this->assertTrue($this->filesystem->writeStream("test", \fopen('php://temp', 'rb')));
    }

    public function testUpdate()
    {
        $this->assertTrue($this->filesystem->update("test", 'content'));
    }

    public function testUpdateStream()
    {
        $this->assertTrue($this->filesystem->updateStream("test", \fopen('php://temp', 'rb')));
    }

    public function testPut()
    {
        $this->assertTrue($this->filesystem->put("test", 'content'));
    }

    public function testPutStream()
    {
        $this->assertTrue($this->filesystem->putStream("test", \fopen('php://temp', 'rb')));
    }

    public function testRename()
    {
        $this->assertTrue($this->filesystem->rename("old", "new"));
    }

    public function testCopy()
    {
        $this->assertTrue($this->filesystem->copy("old", "new"));
    }

    public function testDelete()
    {
        $this->assertTrue($this->filesystem->delete("test"));
    }

    public function testDeleteDir()
    {
        $this->assertTrue($this->filesystem->deleteDir("test"));
    }

    public function testCreateDir()
    {
        $this->assertTrue($this->filesystem->createDir("test"));
    }

    public function testSetVisibility()
    {
        $this->assertTrue($this->filesystem->setVisibility("test", 'private'));
    }

    public function testReadAndDelete()
    {
        $this->assertSame("test", $this->filesystem->readAndDelete("test"));
    }

    public function testConfig()
    {
        $this->assertTrue($this->filesystem->write("check", 'content', new Settings(['check' => false])));
        $this->assertFalse($this->filesystem->write("check", 'content', new Settings(['check' => true])));
    }

    public function testSyncWithRoot()
    {
        $destinationFiles = [
            [
                'type' => 'dir',
                'path' => 'd/dir1',
            ],
            [
                'type' => 'file',
                'path' => 'd/dir1/file1',
            ],
            [
                'type' => 'file',
                'path' => 'd/dir1/file2',
            ],
            [
                'type' => 'dir',
                'path' => 'd/dir2',
            ],
            [
                'type' => 'file',
                'path' => 'd/dir2/file1',
            ],
            [
                'type' => 'file',
                'path' => 'd/dir2/file2',
            ],
        ];

        $sourceFiles = [
            [
                'type' => 'dir',
                'path' => 'dir1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file2',
            ],
            [
                'type' => 'dir',
                'path' => 'dir3',
            ],
            [
                'type' => 'file',
                'path' => 'dir3/file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir3/file2',
            ],
        ];

        $sourceFilesystem = $this->createMock(FilesystemInterface::class);
        $sourceFilesystem->method("listContents")->willReturn($sourceFiles);
        $sourceFilesystem->method("readStream")->willReturn(\fopen("php://temp", "r"));

        $flysystemAdapter = $this->createMock(AdapterInterface::class);
        $flysystemAdapter->method("listContents")->willReturn($destinationFiles);
        $flysystemAdapter->method("deleteDir")->willReturn(true);
        $flysystemAdapter->method("delete")->willReturn(true);
        $flysystemAdapter->method("writeStream")->willReturn(true);
        $flysystemAdapter->method("createDir")->willReturn(true);
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
        $destinationFiles = [
            [
                'type' => 'dir',
                'path' => 'dir1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file2',
            ],
            [
                'type' => 'dir',
                'path' => 'dir2',
            ],
            [
                'type' => 'file',
                'path' => 'dir2/file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir2/file2',
            ],
        ];

        $sourceFiles = [
            [
                'type' => 'dir',
                'path' => 'dir1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir1/file2',
            ],
            [
                'type' => 'dir',
                'path' => 'dir3',
            ],
            [
                'type' => 'file',
                'path' => 'dir3/file1',
            ],
            [
                'type' => 'file',
                'path' => 'dir3/file2',
            ],
        ];

        $sourceFilesystem = $this->createMock(FilesystemInterface::class);
        $sourceFilesystem->method("listContents")->willReturn($sourceFiles);
        $sourceFilesystem->method("readStream")->willReturn(\fopen("php://temp", "r"));

        $flysystemAdapter = $this->createMock(AdapterInterface::class);
        $flysystemAdapter->method("listContents")->willReturn($destinationFiles);
        $flysystemAdapter->method("deleteDir")->willReturn(true);
        $flysystemAdapter->method("delete")->willReturn(true);
        $flysystemAdapter->method("writeStream")->willReturn(true);
        $flysystemAdapter->method("createDir")->willReturn(true);
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
        $destinationFiles = [
            [
                'type' => 'file',
                'path' => 'file1',
            ],
            [
                'type' => 'file',
                'path' => 'file2',
            ],
        ];

        $sourceFiles = [
            [
                'type' => 'file',
                'path' => 'file1',
            ],
            [
                'type' => 'file',
                'path' => 'file3',
            ],
        ];

        $sourceFilesystem = $this->createMock(FilesystemInterface::class);
        $sourceFilesystem->method("listContents")->willReturn($sourceFiles);
        $sourceFilesystem->method("readStream")->willReturn(\fopen("php://temp", "r"));

        $flysystemAdapter = $this->createMock(AdapterInterface::class);
        $flysystemAdapter->method("listContents")->willReturn($destinationFiles);
        $flysystemAdapter->method("deleteDir")->willReturn(true);
        $flysystemAdapter->method("delete")->willReturn(true);
        $flysystemAdapter->method("writeStream")->willReturn(true);
        $flysystemAdapter->method("createDir")->willReturn(true);
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
