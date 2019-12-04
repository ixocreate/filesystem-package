<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorInterface;

final class FilesystemBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new FilesystemConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return "filesystem";
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return "filesystem.php";
    }
}
