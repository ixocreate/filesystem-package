<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Package\Storage;

final class StorageConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * StorageConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        //TODO validation
        $this->config = $config;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getStorageParams(string $name) : array
    {
        if (!\array_key_exists($name, $this->config)) {
            //TODO Exception
        }
        return $this->config[$name];
    }

    /**
     * @return array
     */
    public function getStorageNames() : array
    {
        return \array_keys($this->config);
    }
}
