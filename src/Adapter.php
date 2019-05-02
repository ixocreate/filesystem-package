<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

final class Adapter implements AdapterInterface
{
    /**
     * @var \League\Flysystem\AdapterInterface
     */
    private $adapter;

    /**
     * ProxyAdapter constructor.
     * @param \League\Flysystem\AdapterInterface $adapter
     */
    public function __construct(\League\Flysystem\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return \League\Flysystem\AdapterInterface
     */
    public function adapter(): \League\Flysystem\AdapterInterface
    {
        return $this->adapter;
    }
}
