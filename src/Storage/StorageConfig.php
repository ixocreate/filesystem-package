<?php
/**
 * kiwi-suite/filesystem (https://github.com/kiwi-suite/filesystem)
 *
 * @package kiwi-suite/filesystem
 * @see https://github.com/kiwi-suite/filesystem
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Filesystem\Storage;

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
