<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

interface FilesystemInterface
{
    /**
     * @param string $path
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @param string $path
     * @return string|false
     */
    public function read(string $path);

    /**
     * @param string $path
     * @return resource|false
     */
    public function readStream(string $path);

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function listContents(string $directory = '', bool $recursive = false): array;

    /**
     * @param string $path
     * @return array|false
     */
    public function getMetadata(string $path);

    /**
     * @param string $path
     * @return int|false
     */
    public function getSize(string $path);

    /**
     * @param string $path
     * @return string|false
     */
    public function getMimetype(string $path);

    /**
     * @param string $path
     * @return string|false
     */
    public function getTimestamp(string $path);

    /**
     * @param string $path
     * @return string|false
     */
    public function getVisibility(string $path);

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface $settings
     * @return bool
     */
    public function write(string $path, string $contents, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @return bool
     */
    public function writeStream(string $path, $resource, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface $settings
     * @return bool
     */
    public function update(string $path, string $contents, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @return bool
     */
    public function updateStream(string $path, $resource, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename(string $path, string $newpath): bool;

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function copy(string $path, string $newpath): bool;

    /**
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir(string $dirname): bool;

    /**
     * @param string $dirname
     * @param SettingsInterface $settings
     * @return bool
     */
    public function createDir(string $dirname, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public function setVisibility(string $path, string $visibility): bool;

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface $settings
     * @return bool
     */
    public function put(string $path, string $contents, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface $settings
     * @return bool
     */
    public function putStream(string $path, $resource, ?SettingsInterface $settings = null): bool;

    /**
     * @param string $path
     * @return string|false
     */
    public function readAndDelete(string $path);

    /**
     * @param FilesystemInterface $filesystem
     * @param SettingsInterface|null $settings
     * @return array
     */
    public function syncFrom(FilesystemInterface $filesystem, ?SettingsInterface $settings = null): array;
}
