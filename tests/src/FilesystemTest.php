<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Filesystem;

use Ixocreate\Filesystem\Adapter;
use Ixocreate\Filesystem\Filesystem;
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
                return [];
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
        $this->assertSame([], $this->filesystem->listContents("test"));
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
}
