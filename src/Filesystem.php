<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemReader;

final class Filesystem implements FilesystemInterface
{
    /**
     * @var FilesystemOperator
     */
    private $innerFilesystem;

    /**
     * Filesystem constructor.
     * @param AdapterInterface $adapter
     * @param SettingsInterface|null $settings
     */
    public function __construct(AdapterInterface $adapter, ?SettingsInterface $settings = null)
    {
        $this->innerFilesystem = new \League\Flysystem\Filesystem($adapter->adapter(), $this->createConfig($settings));
    }

    /**
     * @param SettingsInterface|null $settings
     * @return array
     */
    private function createConfig(?SettingsInterface $settings = null): array
    {
        if ($settings === null) {
            return [];
        }

        return $settings->settings();
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return bool
     */
    public function has(string $path): bool
    {
        return $this->innerFilesystem->fileExists($path);
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return string|false
     */
    public function read(string $path)
    {
        return $this->innerFilesystem->read($path);
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return resource|false
     */
    public function readStream(string $path)
    {
        return $this->innerFilesystem->readStream($path);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return DirectoryListing
     */
    public function listContents(string $directory = '', bool $recursive = false): DirectoryListing
    {
        return $this->innerFilesystem->listContents($directory, $recursive ? FilesystemReader::LIST_DEEP : FilesystemReader::LIST_SHALLOW);
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return int|null
     */
    public function getSize(string $path): ?int
    {
        return $this->innerFilesystem->fileSize($path);
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return string|null
     */
    public function getMimetype(string $path): ?string
    {
        return $this->innerFilesystem->mimeType($path);
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return int|null
     */
    public function getTimestamp(string $path): ?int
    {
        return $this->innerFilesystem->lastModified($path);
    }

    /**
     * @param string $path
     * @throws FilesystemException
     * @return string|null
     */
    public function getVisibility(string $path): ?string
    {
        return $this->innerFilesystem->visibility($path);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface|null $settings
     * @throws FilesystemException
     */
    public function write(string $path, string $contents, ?SettingsInterface $settings = null): void
    {
        $this->innerFilesystem->write($path, $contents, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @throws FilesystemException
     */
    public function writeStream(string $path, $resource, ?SettingsInterface $settings = null): void
    {
        $this->innerFilesystem->writeStream($path, $resource, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param string $newPath
     * @param SettingsInterface|null $settings
     * @throws FilesystemException
     */
    public function rename(string $path, string $newPath, ?SettingsInterface $settings = null): void
    {
        $this->innerFilesystem->move($path, $newPath, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param string $newPath
     * @param SettingsInterface|null $settings
     * @throws FilesystemException
     */
    public function copy(string $path, string $newPath, ?SettingsInterface $settings = null): void
    {
        $this->innerFilesystem->copy($path, $newPath, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @throws FilesystemException
     */
    public function delete(string $path): void
    {
        $this->innerFilesystem->delete($path);
    }

    /**
     * @param string $dirname
     */
    public function deleteDir(string $dirname): void
    {
        $this->innerFilesystem->deleteDirectory($dirname);
    }

    /**
     * @param string $dirname
     * @param SettingsInterface|null $settings
     * @throws FilesystemException
     */
    public function createDir(string $dirname, ?SettingsInterface $settings = null): void
    {
        $this->innerFilesystem->createDirectory($dirname, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param string $visibility
     * @throws FilesystemException
     */
    public function setVisibility(string $path, string $visibility): void
    {
        $this->innerFilesystem->setVisibility($path, $visibility);
    }

    public function syncFrom(FilesystemInterface $filesystem, ?SettingsInterface $settings = null): array
    {
        if ($settings === null) {
            $settings = new Settings();
        }

        $sourceRoot = \trim($settings->get('sourceRoot', ''), '/');
        $destinationRoot = \trim($settings->get('destinationRoot', ''), '/');

        $syncResult = (new Sync())
            ->diff(
                $filesystem,
                $this,
                $sourceRoot,
                $destinationRoot
            );

        $result = [
            'update' => [],
            'create' => [],
            'delete' => [],
        ];

        if ($settings->get("doUpdate", true)) {
            foreach ($syncResult['update'] as $item) {
                if ($item['type'] === "dir") {
                    continue;
                }

                if ($item['type'] === "file") {
                    $destinationPath = $item['path'];
                    if (!empty($destinationRoot)) {
                        $destinationPath = $destinationRoot . '/' . $destinationPath;
                    }

                    $sourcePath = $item['path'];
                    if (!empty($sourceRoot)) {
                        $sourcePath = $sourceRoot . '/' . $sourcePath;
                    }
                    $this->writeStream($destinationPath, $filesystem->readStream($sourcePath));
                    $result['update'][] = $destinationPath;
                    continue;
                }
            }
        }

        if ($settings->get("doCreate", true)) {
            foreach ($syncResult['create'] as $item) {
                $destinationPath = $item['path'];
                if (!empty($destinationRoot)) {
                    $destinationPath = $destinationRoot . '/' . $destinationPath;
                }

                $sourcePath = $item['path'];
                if (!empty($sourceRoot)) {
                    $sourcePath = $sourceRoot . '/' . $sourcePath;
                }

                if ($item['type'] === "dir") {
                    $this->createDir($destinationPath, $settings);
                    $result['create'][] = $destinationPath;
                    continue;
                }

                if ($item['type'] === "file") {
                    $this->writeStream($destinationPath, $filesystem->readStream($sourcePath));
                    $result['create'][] = $destinationPath;
                    continue;
                }
            }
        }

        if ($settings->get("doDelete", true)) {
            foreach ($syncResult['delete'] as $item) {
                $destinationPath = $item['path'];

                if ($item['type'] === "dir") {
                    $this->deleteDir($destinationPath);
                    $result['delete'][] = $destinationPath;
                    continue;
                }
                if ($item['type'] === "file") {
                    $this->delete($destinationPath);
                    $result['delete'][] = $destinationPath;
                    continue;
                }
            }
        }

        return $result;
    }
}
