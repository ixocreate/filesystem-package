<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use League\Flysystem\DirectoryListing;

interface FilesystemInterface
{
    /**
     * @param string $path
     * @return bool
     */
    public function has(string $path): bool;

    /**
     * @param string $path
     * @return string
     */
    public function read(string $path);

    /**
     * @param string $path
     * @return resource
     */
    public function readStream(string $path);

    /**
     * @param string $directory
     * @param bool $recursive
     * @return DirectoryListing
     */
    public function listContents(string $directory = '', bool $recursive = false): DirectoryListing;

    /**
     * @param string $path
     * @return int|null
     */
    public function getSize(string $path): ?int;

    /**
     * @param string $path
     * @return string|null
     */
    public function getMimetype(string $path): ?string;

    /**
     * @param string $path
     * @return int|null
     */
    public function getTimestamp(string $path): ?int;

    /**
     * @param string $path
     * @return string|null
     */
    public function getVisibility(string $path): ?string;

    /**
     * @param string $path
     * @param string $contents
     * @param SettingsInterface|null $settings
     */
    public function write(string $path, string $contents, ?SettingsInterface $settings = null): void;

    /**
     * @param string $path
     * @param resource $resource
     * @param SettingsInterface|null $settings
     */
    public function writeStream(string $path, $resource, ?SettingsInterface $settings = null): void;

    /**
     * @param string $path
     * @param string $newPath
     */
    public function rename(string $path, string $newPath): void;

    /**
     * @param string $path
     * @param string $newPath
     */
    public function copy(string $path, string $newPath): void;

    /**
     * @param string $path
     */
    public function delete(string $path): void;

    /**
     * @param string $dirname
     */
    public function deleteDir(string $dirname): void;

    /**
     * @param string $dirname
     * @param SettingsInterface|null $settings
     */
    public function createDir(string $dirname, ?SettingsInterface $settings = null): void;

    /**
     * @param string $path
     * @param string $visibility
     */
    public function setVisibility(string $path, string $visibility): void;

    /**
     * @param FilesystemInterface $filesystem
     * @param SettingsInterface|null $settings
     * @return array
     */
    public function syncFrom(FilesystemInterface $filesystem, ?SettingsInterface $settings = null): array;
}
