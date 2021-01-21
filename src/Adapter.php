<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

use League\Flysystem\FilesystemAdapter;

final class Adapter implements AdapterInterface
{
    /**
     * @var FilesystemAdapter
     */
    private $adapter;

    /**
     * ProxyAdapter constructor.
     * @param FilesystemAdapter $adapter
     */
    public function __construct(FilesystemAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return FilesystemAdapter
     */
    public function adapter(): FilesystemAdapter
    {
        return $this->adapter;
    }
}
