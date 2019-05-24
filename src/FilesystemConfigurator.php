<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\Application\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;

final class FilesystemConfigurator implements ConfiguratorInterface
{
    /**
     * @var OptionInterface[]
     */
    private $storage = [];

    /**
     * @param string $name
     * @param OptionInterface $option
     */
    public function addStorage(string $name, OptionInterface $option)
    {
        $this->storage[$name] = $option;
    }

    /**
     * @return OptionInterface[]
     */
    public function storagePool(): array
    {
        return $this->storage;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(FilesystemConfig::class, new FilesystemConfig($this));
    }
}
