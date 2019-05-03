<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use League\Flysystem\FileNotFoundException;

final class Filesystem implements FilesystemInterface
{
    /**
     * @var \League\Flysystem\FilesystemInterface
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
     * @return bool
     */
    public function has(string $path): bool
    {
        return $this->innerFilesystem->has($path);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return string|false
     */
    public function read(string $path)
    {
        return $this->innerFilesystem->read($path);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return resource|false
     */
    public function readStream(string $path)
    {
        return $this->innerFilesystem->readStream($path);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function listContents(string $directory = '', bool $recursive = false): array
    {
        return $this->innerFilesystem->listContents($directory, $recursive);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return array|false
     */
    public function getMetadata(string $path)
    {
        return $this->innerFilesystem->getMetadata($path);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return int|false
     */
    public function getSize(string $path)
    {
        return $this->innerFilesystem->getSize($path);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return string|false
     */
    public function getMimetype(string $path)
    {
        return $this->innerFilesystem->getMimetype($path);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return string|false
     */
    public function getTimestamp(string $path)
    {
        return $this->innerFilesystem->getTimestamp($path);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return string|false
     */
    public function getVisibility(string $path)
    {
        return $this->innerFilesystem->getVisibility($path);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface $settings
     * @throws \League\Flysystem\FileExistsException
     * @return bool
     */
    public function write(string $path, string $contents, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->write($path, $contents, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @throws \League\Flysystem\FileExistsException
     * @return bool
     */
    public function writeStream(string $path, $resource, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->writeStream($path, $resource, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface $settings
     * @throws \League\Flysystem\FileNotFoundException
     * @return bool
     */
    public function update(string $path, string $contents, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->update($path, $contents, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @throws \League\Flysystem\FileNotFoundException
     * @return bool
     */
    public function updateStream(string $path, $resource, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->updateStream($path, $resource, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param string $newpath
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     * @return bool
     */
    public function rename(string $path, string $newpath): bool
    {
        return $this->innerFilesystem->rename($path, $newpath);
    }

    /**
     * @param string $path
     * @param string $newpath
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     * @return bool
     */
    public function copy(string $path, string $newpath): bool
    {
        return $this->innerFilesystem->copy($path, $newpath);
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return bool
     */
    public function delete(string $path): bool
    {
        return $this->innerFilesystem->delete($path);
    }

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir(string $dirname): bool
    {
        return $this->innerFilesystem->deleteDir($dirname);
    }

    /**
     * @param string $dirname
     * @param SettingsInterface $settings
     * @return bool
     */
    public function createDir(string $dirname, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->createDir($dirname, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param string $visibility
     * @throws \League\Flysystem\FileNotFoundException
     * @return bool
     */
    public function setVisibility(string $path, string $visibility): bool
    {
        return $this->innerFilesystem->setVisibility($path, $visibility);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface $settings
     * @return bool
     */
    public function put(string $path, string $contents, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->put($path, $contents, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @return bool
     */
    public function putStream(string $path, $resource, ?SettingsInterface $settings = null): bool
    {
        return $this->innerFilesystem->put($path, $resource, $this->createConfig($settings));
    }

    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     * @return string|false
     */
    public function readAndDelete(string $path)
    {
        return $this->innerFilesystem->readAndDelete($path);
    }

    public function syncFrom(FilesystemInterface $filesystem, ?SettingsInterface $settings = null): array
    {
        if ($settings === null) {
            $settings = new Settings();
        }

        $sourceRoot = trim($settings->get('sourceRoot', ''), '/');
        $destinationRoot = trim($settings->get('destinationRoot', ''), '/');

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
                    $this->putStream($destinationPath, $filesystem->readStream($sourcePath));
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
