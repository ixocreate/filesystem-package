<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Factory;

use Ixocreate\Filesystem\Exception\FilesystemNotFoundException;
use Ixocreate\Filesystem\Filesystem;
use Ixocreate\Filesystem\FilesystemConfig;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

final class FilesystemFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Exception
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var FilesystemConfig $filesystemConfig */
        $filesystemConfig = $container->get(FilesystemConfig::class);

        if (!$filesystemConfig->has($requestedName)) {
            throw new FilesystemNotFoundException(\sprintf("invalid filesystem name for '%s'", $requestedName));
        }

        return new Filesystem(
            $filesystemConfig->get($requestedName)->create($requestedName, $container)
        );
    }
}
