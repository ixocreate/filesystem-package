<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

final class Sync
{
    /**
     * @param FilesystemInterface $source
     * @param FilesystemInterface $destination
     * @param string $sourceRoot
     * @param string $destinationRoot
     * @return array
     */
    public function diff(
        FilesystemInterface $source,
        FilesystemInterface $destination,
        string $sourceRoot,
        string $destinationRoot
    ): array {
        $sourceRoot = \trim($sourceRoot, '/') ;
        $destinationRoot = \trim($destinationRoot, '/');

        $sourcePaths = $this->getSyncPaths($source, $sourceRoot);
        $destinationPaths = $this->getSyncPaths($destination, $destinationRoot);

        $result = [
            'update' => [],
            'create' => [],
            'delete' => [],
        ];

        foreach ($sourcePaths as $path => $info) {
            if (!\array_key_exists($path, $destinationPaths)) {
                $result['create'][] = $info;
                continue;
            }

            $result['update'][] = $info;
        }

        foreach ($destinationPaths as $path => $info) {
            if (\array_key_exists($path, $sourcePaths)) {
                continue;
            }

            $result['delete'][] = $info;
        }

        $result['delete'] = \array_reverse($result['delete']);

        return $result;
    }

    /**
     * @param FilesystemInterface $filesystem
     * @param $startDirectory
     * @return array
     */
    private function getSyncPaths(FilesystemInterface $filesystem, $startDirectory): array
    {
        $paths = [];
        foreach ($filesystem->listContents($startDirectory, $recursive = true) as $path) {
            $key = $path['path'];
            if (!empty($startDirectory) && \mb_substr($key, 0, \mb_strlen($startDirectory) + 1) === $startDirectory . '/') {
                $key = \mb_substr($key, \mb_strlen($startDirectory) + 1);
            }
            $paths[$key] = $path;
        }
        \ksort($paths);

        return $paths;
    }
}
