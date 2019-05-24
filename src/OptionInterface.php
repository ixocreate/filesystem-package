<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use Ixocreate\ServiceManager\ServiceManagerInterface;

interface OptionInterface extends \Serializable
{
    /**
     * @param string $name
     * @param ServiceManagerInterface $serviceManager
     * @return AdapterInterface
     */
    public function create(string $name, ServiceManagerInterface $serviceManager): AdapterInterface;
}
