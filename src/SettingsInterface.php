<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem;

interface SettingsInterface
{
    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;

    /**
     * @return array
     */
    public function settings(): array;
}
